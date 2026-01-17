<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'marital_status',
        'blood_group',
        'nid',
        'phone',
        'email',
        'present_address',
        'permanent_address',
        'photo',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'joining_date',
        'probation_end_date',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'probation_end_date' => 'date',
        'dob' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
