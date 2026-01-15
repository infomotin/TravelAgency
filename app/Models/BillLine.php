<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillLine extends Model
{
    protected $fillable = [
        'bill_id',
        'account_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
