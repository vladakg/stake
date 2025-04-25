<?php

namespace App\Entities\Investment\Repository;

use App\Entities\Investment\Investment;
use App\Repositories\BaseRepository;

/**
 * Class InvestmentRepository.
 *
 * Repository for the Investment model.
 */
class InvestmentRepository extends BaseRepository implements InvestmentRepositoryInterface
{
    /**
     * InvestmentRepository constructor.
     *
     * @param Investment $investment instance of the Investment model.
     */
    public function __construct(Investment $investment)
    {
        parent::__construct($investment);
    }
}
