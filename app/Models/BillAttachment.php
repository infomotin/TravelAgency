<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillAttachment extends Model
{
    protected $fillable = [
        'bill_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
