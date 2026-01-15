<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\TransactionLine;
use Illuminate\Http\Request;

class AccountingReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:accounting_reports.view');
    }

    public function ledger(Request $request)
    {
        $accounts = Account::where('agency_id', app('currentAgency')->id)->orderBy('code')->get();
        $selectedAccount = null;
        $transactions = [];
        
        if ($request->has('account_id')) {
            $selectedAccount = Account::find($request->account_id);
            $transactions = TransactionLine::where('account_id', $selectedAccount->id)
                ->with('transaction')
                ->whereHas('transaction', function($q) use ($request) {
                    if ($request->date_from) $q->where('date', '>=', $request->date_from);
                    if ($request->date_to) $q->where('date', '<=', $request->date_to);
                })
                ->get()
                ->sortBy('transaction.date');
        }
        
        return view('accounting.reports.ledger', compact('accounts', 'selectedAccount', 'transactions'));
    }

    public function trialBalance(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        
        $accounts = Account::where('agency_id', app('currentAgency')->id)
            ->with(['lines' => function($q) use ($date) {
                $q->whereHas('transaction', function($t) use ($date) {
                    $t->where('date', '<=', $date);
                });
            }])
            ->orderBy('code')
            ->get();
            
        $data = $accounts->map(function($account) {
            $debit = $account->lines->sum('debit');
            $credit = $account->lines->sum('credit');
            $balance = $debit - $credit;
            
            // Adjust balance logic based on account type if needed, 
            // but Trial Balance usually just shows Debit/Credit balances.
            // Assets/Expenses usually Debit, Liab/Equity/Income usually Credit.
            // Here we just show the net result.
            
            return [
                'code' => $account->code,
                'name' => $account->name,
                'debit' => $balance > 0 ? $balance : 0,
                'credit' => $balance < 0 ? abs($balance) : 0,
            ];
        })->filter(function($item) {
            return $item['debit'] > 0 || $item['credit'] > 0;
        });
        
        return view('accounting.reports.trial_balance', compact('data', 'date'));
    }
}
