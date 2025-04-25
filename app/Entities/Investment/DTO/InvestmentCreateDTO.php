<?php

namespace App\Entities\Investment\DTO;

use App\Entities\Campaign\Campaign;

class InvestmentCreateDTO
{
    public function __construct(
        public float $amount,
        public string $guest_identifier,
        public ?int $campaign_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            guest_identifier: $data['guest_identifier'],
            campaign_id: $data['campaign_id'] ?? null,
        );
    }

    public function withCampaign(Campaign $campaign): static
    {
        $this->campaign_id = $campaign->id;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'amount'            => $this->amount,
            'guest_identifier'  => $this->guest_identifier,
            'campaign_id'       => $this->campaign_id
        ];
    }
}
