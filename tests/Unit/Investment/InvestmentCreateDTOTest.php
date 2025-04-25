<?php

namespace Tests\Unit\Investment;

use App\Entities\Investment\DTO\InvestmentCreateDTO;
use App\Entities\Campaign\Campaign;
use PHPUnit\Framework\TestCase;

class InvestmentCreateDTOTest extends TestCase
{
    public function test_from_array_creates_dto_correctly(): void
    {
        $data = [
            'amount'            => 500.0,
            'guest_identifier'  => 'guest@example.com',
            'campaign_id'       => 10,
        ];

        $dto = InvestmentCreateDTO::fromArray($data);

        $this->assertEquals(500.0, $dto->amount);
        $this->assertEquals('guest@example.com', $dto->guest_identifier);
        $this->assertEquals(10, $dto->campaign_id);
    }

    public function test_to_array_returns_expected_data(): void
    {
        $dto = new InvestmentCreateDTO(300.0, 'test@domain.com', 5);

        $expected = [
            'amount'            => 300.0,
            'guest_identifier'  => 'test@domain.com',
            'campaign_id'       => 5,
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_with_campaign_sets_campaign_id(): void
    {
        $dto = new InvestmentCreateDTO(200.0, 'someone@domain.com');

        $campaign = new Campaign();
        $campaign->id = 99;

        $dto->withCampaign($campaign);

        $this->assertEquals(99, $dto->campaign_id);
    }

    public function test_with_campaign_returns_same_instance(): void
    {
        $dto = new InvestmentCreateDTO(100.0, 'abc@example.com');

        $campaign = new Campaign();
        $campaign->id = 77;

        $result = $dto->withCampaign($campaign);

        $this->assertSame($dto, $result);
    }
}
