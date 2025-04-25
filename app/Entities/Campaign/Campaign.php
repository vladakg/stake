<?php

namespace App\Entities\Campaign;

use App\Entities\Campaign\Helpers\CampaignQueryHelper;
use App\Entities\Campaign\Helpers\CampaignRelationsHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory,
        CampaignQueryHelper,
        CampaignRelationsHelper,
        SoftDeletes;

    protected $fillable = [
        'name',
        'image_url',
        'percentage_raised',
        'target_amount',
        'currency',
        'city_area',
        'country',
        'number_of_investors',
        'investment_multiple',
        'current_amount',
    ];

    protected $casts = [
        'target_amount'         => 'decimal:2',
        'current_amount'        => 'decimal:2',
        'percentage_raised'     => 'decimal:2',
        'investment_multiple'   => 'decimal:2',
    ];
}
