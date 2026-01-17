<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'type',
        'company_name',
        'contact_person',
        'designation',
        'mobile',
        'sent_gift',
        'gift_sent_date',
        'last_gift_name',
        'gift_dates',
    ];

    protected $casts = [
        'sent_gift' => 'boolean',
        'gift_sent_date' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(ContactGift::class);
    }
}
