<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'voucher_no',
        'date',
        'type',
        'description',
        'reference',
        'party_id',
        'created_by',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function lines()
    {
        return $this->hasMany(TransactionLine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
