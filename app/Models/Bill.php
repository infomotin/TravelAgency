<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'bill_no',
        'bill_date',
        'due_date',
        'type',
        'contact_name',
        'reference',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lines()
    {
        return $this->hasMany(BillLine::class);
    }

    public function payments()
    {
        return $this->hasMany(BillPayment::class);
    }
}
