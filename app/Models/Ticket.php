<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'branch_id',
        'client_id',
        'employee_id',
        'airline_id',
        'from_airport_id',
        'to_airport_id',
        'ticket_no',
        'pnr',
        'gds',
        'passenger_name',
        'passport_id',
        'pax_type',
        'contact_no',
        'email',
        'date_of_birth',
        'passport_issue_date',
        'passport_expire_date',
        'fare',
        'base_fare',
        'tax',
        'vendor_id',
        'commission_amount',
        'commission_percent',
        'taxes_commission',
        'ait',
        'net_commission',
        'agent_commission_amount',
        'profit_loss',
        'discount',
        'extra_fee',
        'class',
        'ticket_type',
        'segment',
        'issue_date',
        'journey_date',
        'return_date',
        'remarks',
        'tax_amount',
        'commission_7_percent',
        'client_price',
        'purchase_price',
        'profit',
        'country_tax_bd',
        'country_tax_ut',
        'country_tax_e5',
        'country_tax_es',
        'country_tax_xt',
        'country_tax_ow',
        'country_tax_qa',
        'country_tax_pz',
        'country_tax_g4',
        'country_tax_p7',
        'country_tax_p8',
        'country_tax_r9',
        'flight_from',
        'flight_to',
        'flight_airline',
        'flight_no',
        'flight_date',
        'departure_time',
        'arrival_time',
        'invoice_no',
        'sales_date',
        'due_date',
        'agent_id',
    ];

    public function client()
    {
        return $this->belongsTo(Party::class, 'client_id');
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
}
