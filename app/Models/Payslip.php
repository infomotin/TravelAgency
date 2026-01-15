<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payslip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'month',
        'gross',
        'deductions',
        'overtime_amount',
        'net',
        'status',
        'approved_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
