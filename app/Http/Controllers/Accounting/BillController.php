<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bill;
use App\Models\BillAttachment;
use App\Models\BillPayment;
use App\Models\Passport;
use App\Models\Party;
use App\Models\Transaction;
use App\Models\Employee;
use App\Models\Airline;
use App\Models\Product;
use App\Models\TransportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->with(['party', 'creator', 'payments.transaction'])
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

        $employees = DB::table('employees')
            ->where('agency_id', $agencyId)
            ->orderBy('name')
            ->get();

        $passports = Passport::where('agency_id', $agencyId)
            ->orderBy('holder_name')
            ->orderBy('passport_no')
            ->get();

        $airlines = Airline::orderBy('name')->get();

        $airports = DB::table('airports')
            ->orderBy('name')
            ->get();

        $products = Product::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $transportTypes = TransportType::where('agency_id', $agencyId)
            ->orderBy('name')
            ->get();

        $vendors = DB::table('ticket_agencies')
            ->orderBy('name')
            ->get();

        $paymentAccounts = Account::where('agency_id', $agencyId)
            ->whereIn('code', ['1001', '1002'])
            ->orderBy('code')
            ->get();

        return view('accounting.bills.create', compact(
            'accounts',
            'parties',
            'employees',
            'passports',
            'airlines',
            'airports',
            'products',
            'transportTypes',
            'vendors',
            'paymentAccounts'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_date' => 'required|date',
            'due_date' => 'nullable|date',
            'party_id' => 'required|exists:parties,id',
            'employee_id' => 'nullable|exists:employees,id',
            'reference' => 'nullable|string|max:255',
            'lines' => 'required|array|min:1',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'nullable|numeric|min:0',
            'lines.*.unit_price' => 'nullable|numeric|min:0',
            'lines.*.amount' => 'nullable|numeric|min:0',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
            'passport_id' => 'nullable|exists:passports,id',
            'pax_type' => 'nullable|string|max:50',
            'ticket_no' => 'nullable|string|max:100',
            'pnr' => 'nullable|string|max:100',
            'route_text' => 'nullable|string|max:255',
            'ticket_reference' => 'nullable|string|max:255',
            'journey_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'airline_id' => 'nullable|exists:airlines,id',
            'hotel_name' => 'nullable|string|max:255',
            'hotel_reference' => 'nullable|string|max:255',
            'hotel_check_in' => 'nullable|date',
            'hotel_check_out' => 'nullable|date',
            'room_type' => 'nullable|string|max:100',
            'transport_type_id' => 'nullable|exists:transport_types,id',
            'transport_sales_by' => 'nullable|string|max:255',
            'transport_reference' => 'nullable|string|max:255',
            'pickup_place' => 'nullable|string|max:255',
            'pickup_time' => 'nullable',
            'dropoff_place' => 'nullable|string|max:255',
            'dropoff_time' => 'nullable',
            'billing_pax_name' => 'nullable|string|max:255',
            'billing_description' => 'nullable|string',
            'payment_method' => 'nullable|string|max:100',
            'payment_account_id' => 'nullable|exists:accounts,id',
            'payment_amount' => 'nullable|numeric|min:0.01',
            'payment_discount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_note' => 'nullable|string|max:255',
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

        $details = [
            'passport_id' => $validated['passport_id'] ?? null,
            'pax_type' => $validated['pax_type'] ?? null,
            'ticket_no' => $validated['ticket_no'] ?? null,
            'pnr' => $validated['pnr'] ?? null,
            'route_text' => $validated['route_text'] ?? null,
            'ticket_reference' => $validated['ticket_reference'] ?? null,
            'journey_date' => $validated['journey_date'] ?? null,
            'return_date' => $validated['return_date'] ?? null,
            'airline_id' => $validated['airline_id'] ?? null,
            'hotel_name' => $validated['hotel_name'] ?? null,
            'hotel_reference' => $validated['hotel_reference'] ?? null,
            'hotel_check_in' => $validated['hotel_check_in'] ?? null,
            'hotel_check_out' => $validated['hotel_check_out'] ?? null,
            'room_type' => $validated['room_type'] ?? null,
            'transport_type_id' => $validated['transport_type_id'] ?? null,
            'transport_sales_by' => $validated['transport_sales_by'] ?? null,
            'transport_reference' => $validated['transport_reference'] ?? null,
            'pickup_place' => $validated['pickup_place'] ?? null,
            'pickup_time' => $validated['pickup_time'] ?? null,
            'dropoff_place' => $validated['dropoff_place'] ?? null,
            'dropoff_time' => $validated['dropoff_time'] ?? null,
            'billing_pax_name' => $validated['billing_pax_name'] ?? null,
            'billing_description' => $validated['billing_description'] ?? null,
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_discount' => $validated['payment_discount'] ?? null,
            'payment_note' => $validated['payment_note'] ?? null,
        ];

        $bill = Bill::create([
            'agency_id' => $agencyId,
            'bill_no' => $billNo,
            'bill_date' => $validated['bill_date'],
            'due_date' => $validated['due_date'] ?? null,
            'type' => 'sale',
            'party_id' => $validated['party_id'],
            'employee_id' => $validated['employee_id'] ?? null,
            'contact_name' => null, // Deprecated in favor of party_id
            'reference' => $validated['reference'] ?? null,
            'details' => $details,
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

        if (! empty($validated['payment_amount']) && ! empty($validated['payment_account_id'])) {
            $paymentDate = $validated['payment_date'] ?? $validated['bill_date'];

            $arAccountForPayment = Account::where('agency_id', $agencyId)
                ->where('code', '1003')
                ->first();

            if ($arAccountForPayment) {
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
                    'date' => $paymentDate,
                    'type' => 'receipt',
                    'description' => 'Payment for bill '.$billNo,
                    'reference' => $validated['payment_note'] ?? null,
                    'created_by' => auth()->id(),
                    'status' => 'approved',
                ]);

                $transaction->lines()->create([
                    'account_id' => $validated['payment_account_id'],
                    'debit' => $validated['payment_amount'],
                    'credit' => 0,
                    'description' => 'Bill payment',
                ]);

                $transaction->lines()->create([
                    'account_id' => $arAccountForPayment->id,
                    'debit' => 0,
                    'credit' => $validated['payment_amount'],
                    'description' => 'Bill payment',
                ]);

                BillPayment::create([
                    'bill_id' => $bill->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $validated['payment_amount'],
                    'paid_at' => $paymentDate,
                ]);

                $bill->paid_amount = $validated['payment_amount'];
                $bill->balance_amount = $bill->total_amount - $bill->paid_amount;
                $bill->status = $bill->balance_amount <= 0.01 ? 'paid' : 'partial';
                $bill->save();
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
