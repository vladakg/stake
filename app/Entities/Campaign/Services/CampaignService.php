<?php

namespace App\Entities\Campaign\Services;

use App\Entities\Campaign\Campaign;
use App\Entities\Campaign\Repository\CampaignRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CampaignService.
 */
class CampaignService
{
    /**
     * CampaignService constructor.
     * @param CampaignRepositoryInterface $campaignRepository
     */
    public function __construct(protected CampaignRepositoryInterface $campaignRepository)
    {

    }

    /**
     * Retrieves the paginated campaigns.
     *
     * @param  string $sortProperty The property to sort the campaigns by. Default is 'id'.
     * @param  string $sortDirection The direction to sort by. Ascending ('asc') or descending ('desc'). Default is 'asc'.
     * @param  int $perPage The number of campaigns to retrieve per page. Default is 10.
     * @param  array|null $filters Optional filters to apply when retrieving the campaigns.
     * @return LengthAwarePaginator Returns an instance of LengthAwarePaginator with the retrieved campaigns.
     */
    public function getPaginatedCampaigns(
        string $sortProperty = 'id',
        string $sortDirection = 'asc',
        int $perPage = 10,
        ?array $filters = null
    ): LengthAwarePaginator {
        return $this->campaignRepository->paginated($sortProperty, $sortDirection, $perPage, $filters);
    }

    /**
     * Finds a campaign by its ID, or fails.
     * @param int $id
     * @return Model
     * @throws ModelNotFoundException if no model can be found for the given ID
     */
    public function findOrFail(int $id): Model
    {
        return $this->campaignRepository->findOrFail($id);
    }

    /**
     * Updates the campaign after an investment has been made.
     *
     * @param  Campaign $campaign The campaign object that is updated.
     * @param  float $amount The amount of the investment.
     * @param  bool $hasUserInvested Flag if the user has already invested in the campaign or not.
     * @return void
     */
    public function updateAfterInvestment(Campaign $campaign, float $amount, bool $hasUserInvested): void
    {
        $campaign->current_amount += $amount;

        if (!$hasUserInvested) {
            $campaign->number_of_investors++;
        }

        $campaign->percentage_raised = $campaign->calculatePercentageRaised();

        $campaign->save();
    }
}
