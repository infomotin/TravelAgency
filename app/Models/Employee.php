<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'branch_id',
        'user_id',
        'department_id',
        'designation_id',
        'shift_id',
        'employee_code',
        'name',
        'joining_date',
        'probation_end_date',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'probation_end_date' => 'date',
    ];

    public function agency() { return $this->belongsTo(Agency::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function designation() { return $this->belongsTo(Designation::class); }
    public function shift() { return $this->belongsTo(Shift::class); }
}

