<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public static function statusFor(int $agencyId, string $date): ?string
    {
        return static::where('agency_id', $agencyId)->whereDate('date', $date)->value('status');
    }
}
