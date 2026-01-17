<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'in_time',
        'out_time',
        'late_minutes',
        'early_leave_minutes',
        'overtime_minutes',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
