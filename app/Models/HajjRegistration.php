<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Party;
use App\Models\Employee;

class HajjRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'client_id',
        'employee_id',
        'group_name',
        'invoice_no',
        'sales_date',
        'due_date',
        'agent_id',
        'pilgrim_name',
        'tracking_no',
        'pre_reg_year',
        'mobile',
        'date_of_birth',
        'nid',
        'voucher_no',
        'serial_no',
        'gender',
        'maharam',
        'possible_hajj_year',
        'product_id',
        'pax_name',
        'description',
        'quantity',
        'unit_price',
        'cost_price',
        'total_sales',
        'total_cost',
        'profit',
        'vendor_id',
        'sub_total',
        'discount',
        'service_charge',
        'vat_tax',
        'net_total',
        'agent_commission',
        'invoice_due',
        'present_balance',
        'reference',
        'payment_method',
        'account_id',
        'payment_amount',
        'payment_discount',
        'payment_date',
        'receipt_no',
        'payment_note',
    ];

    protected $casts = [
        'sales_date' => 'date',
        'due_date' => 'date',
        'date_of_birth' => 'date',
        'payment_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Party::class, 'client_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
