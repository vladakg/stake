<?php

namespace App\Entities\Campaign\Helpers;

use App\Entities\Investment\Investment;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait CampaignRelationsHelper.
 */
trait CampaignRelationsHelper
{
    /**
     * Defines a "Has Many" relationship with the Investment class.
     * @return HasMany
     */
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }
}
