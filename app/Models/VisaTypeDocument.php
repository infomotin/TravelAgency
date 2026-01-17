<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaTypeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_type_id',
        'name',
        'is_required',
    ];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}
