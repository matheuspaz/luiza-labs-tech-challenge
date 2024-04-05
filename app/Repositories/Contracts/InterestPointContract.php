<?php

namespace App\Repositories\Contracts;

use App\Models\InterestPoint;
use Illuminate\Database\Eloquent\Collection;

interface InterestPointContract
{
    /**
     * Default method to create an interest point.
     *
     * @param InterestPoint $interestPoint Instance of interest point
     * @return bool
     */
    public function create(InterestPoint $interestPoint): bool;

    /**
     * Default method to verify register exists.
     *
     * @param InterestPoint $interestPoint Instance of interest point
     * @return bool
     */
    public function exists(InterestPoint $interestPoint): bool;

    /**
     * Default method to list with filters.
     *
     * @param array $filters filters if available.
     * @return array of interest points.
     */
    public function list(array $filters): Collection;
}
