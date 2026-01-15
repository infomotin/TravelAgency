<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    protected $fillable = [
        'bill_id',
        'transaction_id',
        'amount',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'date',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
