<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'branch_id',
        'airline_id',
        'ticket_no',
        'passenger_name',
        'fare',
        'tax',
        'commission_amount',
        'agent_commission_amount',
        'profit_loss',
        'issue_date',
    ];
}

