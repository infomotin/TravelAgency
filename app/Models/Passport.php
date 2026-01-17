<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'holder_name',
        'mobile',
        'address',
        'local_agent_id',
        'country_id',
        'airport_id',
        'airline_id',
        'ticket_agency_id',
        'currency_id',
        'passport_no',
        'issue_date',
        'expiry_date',
        'document_path',
        'entry_charge',
        'person_commission',
        'is_free',
        'purpose',
        'local_agent_name',
        'local_agent_commission_type',
        'local_agent_commission_value',
        'local_agent_commission_amount',
        'invoice_no',
        'invoice_date',
        'passport_status_id',
    ];

    public function attachments()
    {
        return $this->hasMany(PassportAttachment::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }

    public function status()
    {
        return $this->belongsTo(PassportStatus::class, 'passport_status_id');
    }
}
