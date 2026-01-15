<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirlineCommissionRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'airline_id',
        'type',
        'value',
        'min_fare',
    ];
}

