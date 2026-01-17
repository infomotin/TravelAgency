<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
