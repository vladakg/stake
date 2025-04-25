<?php

namespace App\Http\Controllers;

use App\Entities\Campaign\Campaign;
use App\Entities\Investment\Resource\InvestmentResource;
use App\Entities\Investment\Services\InvestmentService;
use App\Http\Requests\CreateInvestmentRequest;
use Illuminate\Validation\ValidationException;

/**
 * Class InvestmentController.
 */
class InvestmentController extends Controller
{
    /**
     * InvestmentController constructor.
     *
     * @param InvestmentService $investmentService
     */
    public function __construct(protected InvestmentService $investmentService)
    {

    }

    /**
     * Handles the creation of an Investment.
     *
     * @param int $id the Campaign id.
     * @param CreateInvestmentRequest $request the request containing the required data for creating an Investment.
     * @throws ValidationException
     * @return InvestmentResource the created Investment.
     */
    public function store(int $id, CreateInvestmentRequest $request): InvestmentResource
    {
        $investment = $this->investmentService->store($id, $request->validatedDTO());

        return new InvestmentResource($investment);
    }
}
