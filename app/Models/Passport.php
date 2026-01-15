<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'holder_name',
        'mobile',
        'address',
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
    ];

    public function attachments()
    {
        return $this->hasMany(PassportAttachment::class);
    }
}

