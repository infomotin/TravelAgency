<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'basic',
        'house_rent',
        'medical',
        'transport',
        'overtime_rate_per_hour',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
