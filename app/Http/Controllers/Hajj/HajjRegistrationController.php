<?php

namespace App\Http\Controllers\Hajj;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\HajjRegistration;
use App\Models\Party;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HajjRegistrationController extends Controller
{
    public function index()
    {
        $registrations = HajjRegistration::where('agency_id', app('currentAgency')->id)
            ->with(['client', 'employee'])
            ->latest('sales_date')
            ->latest('id')
            ->paginate(20);

        return view('hajj.registrations.index', compact('registrations'));
    }

    public function create()
    {
        $agencyId = app('currentAgency')->id;

        $clients = Party::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $employees = DB::table('employees')
            ->where('agency_id', $agencyId)
            ->orderBy('name')
            ->get();

        $agents = DB::table('local_agents')
            ->where('agency_id', $agencyId)
            ->orderBy('name')
            ->get();

        $products = Product::where('agency_id', $agencyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $vendors = DB::table('ticket_agencies')
            ->orderBy('name')
            ->get();

        $paymentAccounts = Account::where('agency_id', $agencyId)
            ->whereIn('code', ['1001', '1002'])
            ->orderBy('code')
            ->get();

        return view('hajj.registrations.create', compact(
            'clients',
            'employees',
            'agents',
            'products',
            'vendors',
            'paymentAccounts'
        ));
    }

    public function store(Request $request)
    {
        $agencyId = app('currentAgency')->id;

        $validated = $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:parties,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'group_name' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:50'],
            'sales_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'agent_id' => ['nullable', 'integer', 'exists:local_agents,id'],
            'pilgrim_name' => ['nullable', 'string', 'max:255'],
            'tracking_no' => ['nullable', 'string', 'max:100'],
            'pre_reg_year' => ['nullable', 'string', 'max:10'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'nid' => ['nullable', 'string', 'max:100'],
            'voucher_no' => ['nullable', 'string', 'max:100'],
            'serial_no' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'maharam' => ['nullable', 'string', 'max:255'],
            'possible_hajj_year' => ['nullable', 'string', 'max:10'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'pax_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'vendor_id' => ['nullable', 'integer', 'exists:ticket_agencies,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'service_charge' => ['nullable', 'numeric', 'min:0'],
            'vat_tax' => ['nullable', 'numeric', 'min:0'],
            'agent_commission' => ['nullable', 'numeric', 'min:0'],
            'reference' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_discount' => ['nullable', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'receipt_no' => ['nullable', 'string', 'max:100'],
            'payment_note' => ['nullable', 'string'],
        ]);

        $validated['agency_id'] = $agencyId;

        $invoiceNo = trim($validated['invoice_no'] ?? '');
        if ($invoiceNo === '') {
            $validated['invoice_no'] = $this->generateInvoiceNumber($agencyId);
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $unitPrice = (float) ($validated['unit_price'] ?? 0);
        $costPrice = (float) ($validated['cost_price'] ?? 0);

        $totalSales = round($quantity * $unitPrice, 2);
        $totalCost = round($quantity * $costPrice, 2);
        $profit = round($totalSales - $totalCost, 2);

        $discount = (float) ($validated['discount'] ?? 0);
        $serviceCharge = (float) ($validated['service_charge'] ?? 0);
        $vatTax = (float) ($validated['vat_tax'] ?? 0);

        $subTotal = $totalSales;
        $netTotal = round($subTotal - $discount + $serviceCharge + $vatTax, 2);

        $paymentAmount = (float) ($validated['payment_amount'] ?? 0);
        $paymentDiscount = (float) ($validated['payment_discount'] ?? 0);

        $invoiceDue = round($netTotal - $paymentAmount - $paymentDiscount, 2);

        $validated['quantity'] = $quantity;
        $validated['total_sales'] = $totalSales;
        $validated['total_cost'] = $totalCost;
        $validated['profit'] = $profit;
        $validated['sub_total'] = $subTotal;
        $validated['net_total'] = $netTotal;
        $validated['invoice_due'] = $invoiceDue;
        $validated['present_balance'] = $invoiceDue;

        HajjRegistration::create($validated);

        return redirect()
            ->route('hajj.registrations.index')
            ->with('success', 'Hajj registration created successfully.');
    }

    protected function generateInvoiceNumber(int $agencyId): string
    {
        $today = now()->format('Ymd');
        $prefix = 'HAJ-'.$today.'-';

        $lastInvoice = DB::table('hajj_registrations')
            ->where('agency_id', $agencyId)
            ->where('invoice_no', 'like', $prefix.'%')
            ->orderBy('invoice_no', 'desc')
            ->value('invoice_no');

        $nextNumber = 1;

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice);
            $lastSeq = (int) end($parts);
            if ($lastSeq > 0) {
                $nextNumber = $lastSeq + 1;
            }
        }

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
