<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Party;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transactions.view')->only(['index', 'show']);
        $this->middleware('permission:transactions.create')->only(['create', 'store']);
        $this->middleware('permission:transactions.update')->only(['edit', 'update']);
        $this->middleware('permission:transactions.delete')->only(['destroy']);
    }

    public function index()
    {
        $transactions = Transaction::where('agency_id', app('currentAgency')->id)
            ->with('lines.account', 'creator', 'party')
            ->latest('date')
            ->paginate(15);

        return view('accounting.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::where('agency_id', app('currentAgency')->id)
            ->orderBy('code')
            ->get();
        $parties = Party::where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('accounting.transactions.create', compact('accounts', 'parties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:payment,receipt,journal,contra',
            'description' => 'nullable|string',
            'reference' => 'nullable|string',
            'party_id' => 'nullable|exists:parties,id',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);
        
        // Validate balance
        $totalDebit = collect($validated['lines'])->sum('debit');
        $totalCredit = collect($validated['lines'])->sum('credit');
        
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['lines' => 'Debits must equal Credits. Difference: ' . abs($totalDebit - $totalCredit)])->withInput();
        }

        // Generate voucher number
        $prefix = strtoupper(substr($validated['type'], 0, 2)); // PA, RE, JO, CO
        
        $lastVoucher = Transaction::where('agency_id', app('currentAgency')->id)
            ->where('voucher_no', 'like', $prefix . '%')
            ->latest('id')
            ->first();
            
        $number = 1;
        if ($lastVoucher) {
            // Extract number from PA000001
            $number = intval(substr($lastVoucher->voucher_no, 2)) + 1;
        }
        $voucherNo = $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);

        $transaction = Transaction::create([
            'agency_id' => app('currentAgency')->id,
            'voucher_no' => $voucherNo,
            'date' => $validated['date'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'reference' => $validated['reference'],
            'party_id' => $validated['party_id'] ?? null,
            'created_by' => auth()->id(),
            'status' => 'approved',
        ]);

        foreach ($validated['lines'] as $line) {
            if ($line['debit'] > 0 || $line['credit'] > 0) {
                $transaction->lines()->create($line);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded successfully.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('lines.account', 'creator', 'party');
        return view('accounting.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load('lines', 'party');
        $accounts = Account::where('agency_id', app('currentAgency')->id)
            ->orderBy('code')
            ->get();
        $parties = Party::where('agency_id', app('currentAgency')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('accounting.transactions.edit', compact('transaction', 'accounts', 'parties'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:payment,receipt,journal,contra',
            'description' => 'nullable|string',
            'reference' => 'nullable|string',
            'party_id' => 'nullable|exists:parties,id',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);
        
        $totalDebit = collect($validated['lines'])->sum('debit');
        $totalCredit = collect($validated['lines'])->sum('credit');
        
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['lines' => 'Debits must equal Credits.'])->withInput();
        }

        $transaction->update([
            'date' => $validated['date'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'reference' => $validated['reference'],
            'party_id' => $validated['party_id'] ?? null,
        ]);

        // Delete old lines and re-create (simplest approach)
        $transaction->lines()->delete();

        foreach ($validated['lines'] as $line) {
            if ($line['debit'] > 0 || $line['credit'] > 0) {
                $transaction->lines()->create($line);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->lines()->delete();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
