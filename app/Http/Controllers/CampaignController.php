<?php

namespace App\Http\Controllers;

use App\Entities\Campaign\Resource\CampaignResource;
use App\Entities\Campaign\Services\CampaignService;
use App\Http\Requests\PaginatedCampaignRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class CampaignController.
 */
class CampaignController extends Controller
{
    /**
     * CampaignController constructor.
     * @param CampaignService $campaignService
     */
    public function __construct(protected CampaignService $campaignService)
    {

    }

    /**
     * Returns a paginated list of all campaigns wrapped in a resource collection.
     * @param PaginatedCampaignRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(PaginatedCampaignRequest $request): AnonymousResourceCollection
    {
        $sortProperty   = $request->input('sortProperty', 'id');
        $sortDirection  = $request->input('sortDirection', 'asc');
        $perPage        = $request->input('size', 10);
        $filters        = $request->only(['search', 'number_of_investors', 'target_amount', 'percentage_raised']);

        $campaigns = $this->campaignService->getPaginatedCampaigns($sortProperty, $sortDirection, $perPage, $filters);

        return CampaignResource::collection($campaigns);
    }

    /**
     * Returns a specific campaign wrapped in a resource.
     * @param int $id
     * @return CampaignResource
     * @throws ModelNotFoundException if no campaign can be found for the given ID
     */
    public function show(int $id): CampaignResource
    {
        $campaign = $this->campaignService->findOrFail($id);

        return new CampaignResource($campaign);
    }
}
