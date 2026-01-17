<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'date',
        'name',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public static function forAgencyAndDate(int $agencyId, string $date): ?self
    {
        return static::where('agency_id', $agencyId)
            ->whereDate('date', $date)
            ->first();
    }

    public static function isHoliday(int $agencyId, string $date): bool
    {
        return static::forAgencyAndDate($agencyId, $date) !== null;
    }
}
