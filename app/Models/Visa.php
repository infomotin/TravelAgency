<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'passport_id',
        'country_id',
        'visa_type',
        'issue_date',
        'expiry_date',
        'visa_fee',
        'visa_type_id',
        'agent_id',
        'agent_commission',
        'document_path',
        'invoice_no',
        'invoice_date',
    ];

    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }

    public function type()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id');
    }

    public function documents()
    {
        return $this->hasMany(VisaDocument::class);
    }
}
