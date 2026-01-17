<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_id',
        'visa_type_document_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    public function typeDefinition()
    {
        return $this->belongsTo(VisaTypeDocument::class, 'visa_type_document_id');
    }
}
