<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bill;
use App\Models\BillAttachment;
use App\Models\BillPayment;
use App\Models\Party;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:accounts.view')->only(['index', 'show']);
        $this->middleware('permission:accounts.create')->only(['create', 'store']);
        $this->middleware('permission:accounts.update')->only(['edit', 'update']);
        $this->middleware('permission:accounts.delete')->only(['destroy']);
    }

    public function index()
    {
        $bills = Bill::where('agency_id', app('currentAgency')->id)
            ->with('party')
            ->latest('bill_date')
            ->paginate(15);

        return view('accounting.bills.index', compact('bills'));
    }

    public function create()
    {
        $agencyId = app('currentAgency')->id;
        $accounts = Account::where('agency_id', $agencyId)
            ->orderBy('code')
            ->get();

        $parties = Party::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('accounting.bills.create', compact('accounts', 'parties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_date' => 'required|date',
            'due_date' => 'nullable|date',
            'party_id' => 'required|exists:parties,id',
            'reference' => 'nullable|string|max:255',
            'lines' => 'required|array|min:1',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'nullable|numeric|min:0',
            'lines.*.unit_price' => 'nullable|numeric|min:0',
            'lines.*.amount' => 'nullable|numeric|min:0',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $lines = collect($validated['lines'])->map(function ($line) {
            $qty = $line['quantity'] ?? 1;
            $price = $line['unit_price'] ?? 0;
            $amount = $line['amount'] ?? ($qty * $price);

            return [
                'account_id' => $line['account_id'],
                'description' => $line['description'] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'amount' => $amount,
            ];
        })->filter(function ($line) {
            return $line['amount'] > 0;
        });

        if ($lines->isEmpty()) {
            return back()->withErrors(['lines' => 'Please enter at least one line with amount.'])->withInput();
        }

        $totalAmount = $lines->sum('amount');

        $agencyId = app('currentAgency')->id;

        $lastBill = Bill::where('agency_id', $agencyId)
            ->latest('id')
            ->first();
        $nextNumber = $lastBill ? (intval(substr($lastBill->bill_no, 2)) + 1) : 1;
        $billNo = 'BL'.str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $bill = Bill::create([
            'agency_id' => $agencyId,
            'bill_no' => $billNo,
            'bill_date' => $validated['bill_date'],
            'due_date' => $validated['due_date'] ?? null,
            'type' => 'sale',
            'party_id' => $validated['party_id'],
            'contact_name' => null, // Deprecated in favor of party_id
            'reference' => $validated['reference'] ?? null,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance_amount' => $totalAmount,
            'status' => 'open',
            'created_by' => auth()->id(),
        ]);

        foreach ($lines as $line) {
            $bill->lines()->create($line);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('bill_attachments', 'public');
                $bill->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        $arAccount = Account::where('agency_id', $agencyId)
            ->where('code', '1003')
            ->first();

        if ($arAccount) {
            $prefix = 'IN';
            $lastVoucher = Transaction::where('agency_id', $agencyId)
                ->where('voucher_no', 'like', $prefix.'%')
                ->latest('id')
                ->first();
            $number = $lastVoucher ? intval(substr($lastVoucher->voucher_no, 2)) + 1 : 1;
            $voucherNo = $prefix.str_pad($number, 6, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'agency_id' => $agencyId,
                'voucher_no' => $voucherNo,
                'date' => $validated['bill_date'],
                'type' => 'journal',
                'description' => 'Bill '.$billNo,
                'reference' => $bill->reference,
                'created_by' => auth()->id(),
                'status' => 'approved',
            ]);

            $transaction->lines()->create([
                'account_id' => $arAccount->id,
                'debit' => $totalAmount,
                'credit' => 0,
                'description' => 'Accounts Receivable for bill '.$billNo,
            ]);

            foreach ($lines as $line) {
                $transaction->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => 0,
                    'credit' => $line['amount'],
                    'description' => $line['description'],
                ]);
            }
        }

        return redirect()->route('bills.index')->with('success', 'Bill created successfully.');
    }

    public function show(Bill $bill)
    {
        $bill->load('lines.account', 'payments.transaction');

        return view('accounting.bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $agencyId = app('currentAgency')->id;
        $accounts = Account::where('agency_id', $agencyId)
            ->orderBy('code')
            ->get();

        $parties = Party::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $bill->load('lines');

        return view('accounting.bills.edit', compact('bill', 'accounts', 'parties'));
    }

    public function update(Request $request, Bill $bill)
    {
        if ($bill->status !== 'open') {
            return back()->with('error', 'Only open bills can be edited.');
        }

        $validated = $request->validate([
            'bill_date' => 'required|date',
            'due_date' => 'nullable|date',
            'party_id' => 'required|exists:parties,id',
            'reference' => 'nullable|string|max:255',
            'lines' => 'required|array|min:1',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'nullable|numeric|min:0',
            'lines.*.unit_price' => 'nullable|numeric|min:0',
            'lines.*.amount' => 'nullable|numeric|min:0',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $lines = collect($validated['lines'])->map(function ($line) {
            $qty = $line['quantity'] ?? 1;
            $price = $line['unit_price'] ?? 0;
            $amount = $line['amount'] ?? ($qty * $price);

            return [
                'account_id' => $line['account_id'],
                'description' => $line['description'] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'amount' => $amount,
            ];
        })->filter(function ($line) {
            return $line['amount'] > 0;
        });

        if ($lines->isEmpty()) {
            return back()->withErrors(['lines' => 'Please enter at least one line with amount.'])->withInput();
        }

        $totalAmount = $lines->sum('amount');

        $bill->update([
            'bill_date' => $validated['bill_date'],
            'due_date' => $validated['due_date'] ?? null,
            'party_id' => $validated['party_id'],
            'contact_name' => null, // Deprecated
            'reference' => $validated['reference'] ?? null,
            'total_amount' => $totalAmount,
            'balance_amount' => $totalAmount - $bill->paid_amount,
        ]);

        $bill->lines()->delete();
        foreach ($lines as $line) {
            $bill->lines()->create($line);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('bill_attachments', 'public');
                $bill->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('bills.show', $bill)->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        if ($bill->payments()->exists()) {
            return back()->with('error', 'Cannot delete bill with payments.');
        }

        $bill->lines()->delete();
        $bill->delete();

        return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
    }

    public function payForm(Bill $bill)
    {
        if ($bill->balance_amount <= 0) {
            return redirect()->route('bills.show', $bill)->with('error', 'Bill is already fully paid.');
        }

        $accounts = Account::where('agency_id', app('currentAgency')->id)
            ->whereIn('code', ['1001', '1002'])
            ->orderBy('code')
            ->get();

        return view('accounting.bills.pay', compact('bill', 'accounts'));
    }

    public function storePayment(Request $request, Bill $bill)
    {
        if ($bill->balance_amount <= 0) {
            return redirect()->route('bills.show', $bill)->with('error', 'Bill is already fully paid.');
        }

        $validated = $request->validate([
            'paid_at' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validated['amount'] > $bill->balance_amount + 0.01) {
            return back()->withErrors(['amount' => 'Payment exceeds bill balance.'])->withInput();
        }

        $agencyId = app('currentAgency')->id;

        $arAccount = Account::where('agency_id', $agencyId)
            ->where('code', '1003')
            ->first();

        if (! $arAccount) {
            return back()->withErrors(['amount' => 'Accounts Receivable account (1003) not found.'])->withInput();
        }

        $prefix = 'RC';
        $lastVoucher = Transaction::where('agency_id', $agencyId)
            ->where('voucher_no', 'like', $prefix.'%')
            ->latest('id')
            ->first();
        $number = $lastVoucher ? intval(substr($lastVoucher->voucher_no, 2)) + 1 : 1;
        $voucherNo = $prefix.str_pad($number, 6, '0', STR_PAD_LEFT);

        $transaction = Transaction::create([
            'agency_id' => $agencyId,
            'voucher_no' => $voucherNo,
            'date' => $validated['paid_at'],
            'type' => 'receipt',
            'description' => 'Payment for bill '.$bill->bill_no,
            'reference' => $validated['description'] ?? null,
            'created_by' => auth()->id(),
            'status' => 'approved',
        ]);

        $transaction->lines()->create([
            'account_id' => $validated['account_id'],
            'debit' => $validated['amount'],
            'credit' => 0,
            'description' => 'Bill payment',
        ]);

        $transaction->lines()->create([
            'account_id' => $arAccount->id,
            'debit' => 0,
            'credit' => $validated['amount'],
            'description' => 'Bill payment',
        ]);

        BillPayment::create([
            'bill_id' => $bill->id,
            'transaction_id' => $transaction->id,
            'amount' => $validated['amount'],
            'paid_at' => $validated['paid_at'],
        ]);

        $bill->paid_amount += $validated['amount'];
        $bill->balance_amount = $bill->total_amount - $bill->paid_amount;
        $bill->status = $bill->balance_amount <= 0.01 ? 'paid' : 'partial';
        $bill->save();

        return redirect()->route('bills.show', $bill)->with('success', 'Payment recorded successfully.');
    }

    public function destroyAttachment(BillAttachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}
