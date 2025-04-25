<?php

namespace App\Entities\Campaign\Repository;

use App\Entities\Campaign\Campaign;
use App\Entities\Campaign\Filters\CampaignFilter;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CampaignRepository.
 *
 * Repository for Campaign model.
 */
class CampaignRepository extends BaseRepository implements CampaignRepositoryInterface
{
    /**
     * CampaignRepository constructor.
     *
     * @param Campaign $campaign instance of Campaign
     */
    public function __construct(Campaign $campaign, protected CampaignFilter $campaignFilter)
    {
        parent::__construct($campaign);
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
    public function paginated(
        string $sortProperty = 'id',
        string $sortDirection = 'asc',
        int $perPage = 10,
        ?array $filters = null
    ): LengthAwarePaginator {
        $query = $this->model
            ->newQuery()
            ->orderBy($sortProperty, $sortDirection);

        $query = $this->campaignFilter->apply($query, $filters);

        return $query->paginate($perPage);
    }
}
