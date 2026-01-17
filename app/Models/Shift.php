<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['agency_id', 'name', 'start_time', 'end_time', 'grace_minutes'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
