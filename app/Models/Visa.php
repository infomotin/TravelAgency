<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'passport_id',
        'visa_type',
        'issue_date',
        'expiry_date',
        'document_path',
    ];
}

