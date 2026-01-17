<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'iata_code',
        'country',
        'contact_email',
        'contact_phone',
        'status',
    ];
}
