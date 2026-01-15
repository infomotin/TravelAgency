<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'name',
        'code',
        'address',
        'phone',
        'status',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

