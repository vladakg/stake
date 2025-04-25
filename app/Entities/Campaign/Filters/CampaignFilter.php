<?php

namespace App\Entities\Campaign\Filters;

use App\Repositories\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Filters for Campaign.
 */
class CampaignFilter extends BaseFilter
{
    /**
     * Filters number of investors.
     */
    public function filterNumberOfInvestors(Builder $query, array|string $value): Builder
    {
        if (is_array($value)) {
            if ($min = Arr::get($value, 'min')) {
                $query->where('number_of_investors', '>=', (int)$min);
            }
            if ($max = Arr::get($value, 'max')) {
                $query->where('number_of_investors', '<=', (int)$max);
            }
        } else {
            $query->where('number_of_investors', (int)$value);
        }

        return $query;
    }

    /**
     * Filters by search keyword.
     */
    public function filterSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('city_area', 'like', '%' . $keyword . '%')
                ->orWhere('country', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * Filters by target amount.
     */
    public function filterTargetAmount(Builder $query, array $value): Builder
    {
        if ($min = Arr::get($value, 'min')) {
            $query->where('target_amount', '>=', (float)$min);
        }
        if ($max = Arr::get($value, 'max')) {
            $query->where('target_amount', '<=', (float)$max);
        }

        return $query;
    }

    /**
     * Filters by percentage raised.
     */
    public function filterPercentageRaised(Builder $query, array $value): Builder
    {
        if ($min = Arr::get($value, 'min')) {
            $query->where('percentage_raised', '>=', (float)$min);
        }
        if ($max = Arr::get($value, 'max')) {
            $query->where('percentage_raised', '<=', (float)$max);
        }

        return $query;
    }
}
