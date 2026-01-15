<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'holder_name',
        'passport_no',
        'issue_date',
        'expiry_date',
        'document_path',
    ];
}

