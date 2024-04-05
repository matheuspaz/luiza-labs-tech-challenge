<?php

namespace App\Repositories\Contracts;

use App\Models\InterestPoint;

interface InterestPointContract
{
    /**
     * Default method to create an interest point.
     *
     * @param InterestPoint $interestPoint Instance of interest point
     * @return bool
     */
    public function create(InterestPoint $interestPoint): bool;
}
