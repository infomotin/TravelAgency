<?php

namespace App\Services;

use App\Models\AirlineCommissionRule;

class CommissionService
{
    public function calculateForAirline(int $airlineId, float $fare): float
    {
        $rule = AirlineCommissionRule::where('airline_id', $airlineId)
            ->where('min_fare', '<=', $fare)
            ->orderByDesc('min_fare')
            ->first();
        if (!$rule) {
            return 0.0;
        }
        if ($rule->type === 'percentage') {
            return round(($fare * $rule->value) / 100, 2);
        }
        return round($rule->value, 2);
    }
}

