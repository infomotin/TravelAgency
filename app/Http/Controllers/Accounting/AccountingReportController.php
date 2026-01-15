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

    public function profitLoss(Request $request)
    {
        $period = $request->input('period', 'month');
        $today = date('Y-m-d');
        if ($period === 'year') {
            $from = date('Y-01-01');
            $to = date('Y-12-31');
        } else {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
        }

        if ($request->filled('date_from')) {
            $from = $request->input('date_from');
        }
        if ($request->filled('date_to')) {
            $to = $request->input('date_to');
        }

        $accounts = Account::where('agency_id', app('currentAgency')->id)
            ->whereIn('type', ['income', 'expense'])
            ->with(['lines' => function ($q) use ($from, $to) {
                $q->whereHas('transaction', function ($t) use ($from, $to) {
                    $t->whereBetween('date', [$from, $to]);
                });
            }])
            ->orderBy('type')
            ->orderBy('code')
            ->get();

        $income = [];
        $expenses = [];
        $totalIncome = 0;
        $totalExpenses = 0;

        foreach ($accounts as $account) {
            $debit = $account->lines->sum('debit');
            $credit = $account->lines->sum('credit');

            if ($account->type === 'income') {
                $net = $credit - $debit;
                if (abs($net) < 0.01) {
                    continue;
                }
                $income[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $net,
                ];
                $totalIncome += $net;
            } else {
                $net = $debit - $credit;
                if (abs($net) < 0.01) {
                    continue;
                }
                $expenses[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $net,
                ];
                $totalExpenses += $net;
            }
        }

        $profit = $totalIncome - $totalExpenses;

        return view('accounting.reports.profit_loss', [
            'period' => $period,
            'dateFrom' => $from,
            'dateTo' => $to,
            'income' => $income,
            'expenses' => $expenses,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'profit' => $profit,
        ]);
    }
}
