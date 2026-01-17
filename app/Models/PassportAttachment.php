<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'passport_id',
        'type',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }
}
