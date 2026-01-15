<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
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
        $accounts = Account::where('agency_id', app('currentAgency')->id)
            ->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('code')
            ->get();

        return view('accounting.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parents = Account::where('agency_id', app('currentAgency')->id)
            ->orderBy('code')
            ->get();
            
        return view('accounting.accounts.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        $validated['agency_id'] = app('currentAgency')->id;
        
        // Ensure unique code per agency
        $exists = Account::where('agency_id', $validated['agency_id'])
            ->where('code', $validated['code'])
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['code' => 'Account code already exists.'])->withInput();
        }

        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function edit(Account $account)
    {
        $parents = Account::where('agency_id', app('currentAgency')->id)
            ->where('id', '!=', $account->id)
            ->orderBy('code')
            ->get();

        return view('accounting.accounts.edit', compact('account', 'parents'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        // Ensure unique code per agency excluding current
        $exists = Account::where('agency_id', $account->agency_id)
            ->where('code', $validated['code'])
            ->where('id', '!=', $account->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['code' => 'Account code already exists.'])->withInput();
        }

        $account->update($validated);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        if ($account->lines()->exists()) {
            return back()->with('error', 'Cannot delete account with existing transactions.');
        }

        if ($account->children()->exists()) {
            return back()->with('error', 'Cannot delete account with child accounts.');
        }

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}
