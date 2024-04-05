<?php

namespace App\Services;

use App\Models\InterestPoint;
use App\Repositories\InterestPointRepository;

class InterestPointService
{
    /**
     * Instance of InterestRepository
     */
    private InterestPointRepository $interestRepository;

    /**
     * @param InterestRepository $interestRepository DI object of repository
     */
    public function __construct(interestPointRepository $interestRepository)
    {
        $this->interestRepository = $interestRepository;
    }

    /**
     * Create a new interest point.
     *
     * @param InterestPoint $interestPoint Interest point
     * @return boolean
     */
    public function create(InterestPoint $interestPoint): bool
    {
        return $this->interestRepository->create(interestPoint: $interestPoint);
    }
}
