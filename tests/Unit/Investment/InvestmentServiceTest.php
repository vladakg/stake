<?php

namespace Tests\Unit\Investment;

use App\Entities\Campaign\Campaign;
use App\Entities\Campaign\Repository\CampaignRepositoryInterface;
use App\Entities\Campaign\Services\CampaignService;
use App\Entities\Investment\DTO\InvestmentCreateDTO;
use App\Entities\Investment\Investment;
use App\Entities\Investment\Repository\InvestmentRepositoryInterface;
use App\Entities\Investment\Services\InvestmentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class InvestmentServiceTest extends TestCase
{
    protected InvestmentService $investmentService;
    protected MockObject $investmentRepository;
    protected MockObject $campaignRepository;
    protected MockObject $campaignService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->investmentRepository = $this->createMock(InvestmentRepositoryInterface::class);
        $this->campaignRepository = $this->createMock(CampaignRepositoryInterface::class);
        $this->campaignService = $this->createMock(CampaignService::class);

        $this->investmentService = new InvestmentService(
            $this->investmentRepository,
            $this->campaignRepository,
            $this->campaignService
        );
    }

    public function test_it_stores_an_investment_successfully()
    {
        $campaignId = 1;
        $investmentData = new InvestmentCreateDTO(500, 'guest@example.com');

        $campaignMock = $this->createMock(Campaign::class);
        $campaignMock->method('validateInvestmentAmount')
            ->willReturn(true);
        $campaignMock->method('getRemainingInvestmentAmount')
            ->willReturn(1000.00);
        $campaignMock->method('hasUserInvested')
            ->willReturn(false);

        $this->campaignRepository->expects($this->once())
            ->method('findAndLockForUpdate')
            ->with($campaignId)
            ->willReturn($campaignMock);

        $this->investmentRepository->expects($this->once())
            ->method('create')
            ->willReturn(new Investment());

        $this->campaignService->expects($this->once())
            ->method('updateAfterInvestment')
            ->with($campaignMock, 500, false);

        $investment = $this->investmentService->store($campaignId, $investmentData);

        $this->assertInstanceOf(Model::class, $investment);
    }

    public function test_it_throws_exception_if_investment_amount_is_invalid(): void
    {
        $campaignId = 1;
        $investmentData = new InvestmentCreateDTO(300, 'guest@example.com');

        $campaignMock = $this->getMockBuilder(Campaign::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateInvestmentAmount'])
            ->getMock();

        $campaignMock->method('validateInvestmentAmount')->willReturn(false);
        $campaignMock->investment_multiple = 101;
        $this->campaignRepository->expects($this->once())
            ->method('findAndLockForUpdate')
            ->with($campaignId)
            ->willReturn($campaignMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Investment amount must be a multiple of 101.00 AED.');

        $this->investmentService->store($campaignId, $investmentData);
    }

    public function test_it_throws_exception_if_investment_amount_exceeds_remaining_amount()
    {
        $campaignId = 1;
        $investmentData = new InvestmentCreateDTO(200, 'guest@example.com');

        $campaignMock = $this->createMock(Campaign::class);
        $campaignMock->method('validateInvestmentAmount')
            ->willReturn(true);
        $campaignMock->method('getRemainingInvestmentAmount')
            ->willReturn(100.00);

        $this->campaignRepository->expects($this->once())
            ->method('findAndLockForUpdate')
            ->with($campaignId)
            ->willReturn($campaignMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The amount exceeds the remaining investment space for this campaign.');

        $this->investmentService->store($campaignId, $investmentData);
    }
}
