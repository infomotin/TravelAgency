<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisaType extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'default_fee',
        'status',
    ];

    public function documents()
    {
        return $this->hasMany(VisaTypeDocument::class);
    }
}
