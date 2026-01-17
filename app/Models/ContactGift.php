<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactGift extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'gift_date',
        'gift_name',
        'remark',
    ];

    protected $casts = [
        'gift_date' => 'date',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}

