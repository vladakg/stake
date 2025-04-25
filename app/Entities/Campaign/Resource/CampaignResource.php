<?php

namespace App\Entities\Campaign\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'image_url'             => $this->image_url,
            'percentage_raised'     => $this->percentage_raised,
            'target_amount'         => $this->target_amount,
            'city_area'             => $this->city_area,
            'country'               => $this->country,
            'number_of_investors'   => $this->number_of_investors,
            'investment_multiple'   => $this->investment_multiple,
        ];
    }
}
