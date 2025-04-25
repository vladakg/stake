<?php

namespace App\Entities\Investment\Resource;

use App\Entities\Campaign\Resource\CampaignResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'campaign_id'       => $this->when(!$this->relationLoaded('campaign'), $this->campaign_id),
            'campaign'          => new CampaignResource($this->whenLoaded('campaign')),
            'amount'            => $this->amount,
            'guest_identifier'  => $this->guest_identifier,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at
        ];
    }
}
