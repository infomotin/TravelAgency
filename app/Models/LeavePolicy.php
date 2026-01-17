<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePolicy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['agency_id', 'name', 'annual_quota', 'carry_forward'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
