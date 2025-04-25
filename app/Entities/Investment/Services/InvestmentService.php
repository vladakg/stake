<?php

namespace App\Entities\Investment\Services;

use App\Entities\Campaign\Repository\CampaignRepositoryInterface;
use App\Entities\Campaign\Services\CampaignService;
use App\Entities\Investment\DTO\InvestmentCreateDTO;
use App\Entities\Investment\Repository\InvestmentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

/**
 * Class InvestmentService.
 */
class InvestmentService
{
    /**
     * InvestmentService constructor.
     *
     * @param InvestmentRepositoryInterface $investmentRepository
     * @param CampaignService $campaignService
     */
    public function __construct(protected InvestmentRepositoryInterface $investmentRepository,
                                protected CampaignRepositoryInterface $campaignRepository,
                                protected CampaignService $campaignService)
    {

    }

    /**
     * Stores an Investment.
     *
     * @param int $campaign_id Campaign id
     * @param InvestmentCreateDTO $investmentCreateDTO data transfer object containing new Investment data.
     * @throws ValidationException
     * @return Model the created Investment entity.
     */
    public function store(int $campaign_id, InvestmentCreateDTO $investmentCreateDTO): Model
    {
        return DB::transaction(function () use ($campaign_id, $investmentCreateDTO) {
            $campaign = $this->campaignRepository->findAndLockForUpdate($campaign_id);

            if (!$campaign->validateInvestmentAmount($investmentCreateDTO->amount)) {
                throw ValidationException::withMessages([
                    'amount' => "Investment amount must be a multiple of {$campaign->investment_multiple} AED.",
                ]);
            }

            if ($campaign->getRemainingInvestmentAmount() < $investmentCreateDTO->amount) {
                throw ValidationException::withMessages([
                    'amount' => 'The amount exceeds the remaining investment space for this campaign.',
                ]);
            }

            $hasUserInvested = $campaign->hasUserInvested($investmentCreateDTO->guest_identifier);

            $investment = $this->investmentRepository->create($investmentCreateDTO->withCampaign($campaign)->toArray());

            $this->campaignService->updateAfterInvestment($campaign, $investmentCreateDTO->amount, $hasUserInvested);

            return $investment;
        });
    }
}
