<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'type',
        'name',
        'email',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'country',
        'tax_number',
        'opening_balance',
        'status',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
