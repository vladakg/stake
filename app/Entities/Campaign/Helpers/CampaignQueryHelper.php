<?php

namespace App\Entities\Campaign\Helpers;

trait CampaignQueryHelper
{
    public function calculatePercentageRaised(): float
    {
        return round(($this->current_amount / $this->target_amount) * 100, 2);
    }

    public function getRemainingInvestmentAmount(): float
    {
        return $this->target_amount - $this->current_amount;
    }

    public function validateInvestmentAmount(float $amount): bool
    {
        $mod = fmod($amount, $this->investment_multiple);
        return abs($mod) < 0.00001;
    }

    public function hasUserInvested(string $identifier): bool
    {
        return $this->investments()
            ->where('guest_identifier', $identifier)
            ->exists();
    }
}
