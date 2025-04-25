<?php

namespace App\Entities\Investment\Helpers;

use App\Entities\Campaign\Campaign;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait InvestmentRelationsHelper.
 */
trait InvestmentRelationsHelper
{
    /**
     * Defines a "Belongs To" relationship with the Campaign class.
     * @return BelongsTo
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
