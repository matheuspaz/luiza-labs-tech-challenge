<?php

namespace App\Repositories;

use App\Models\InterestPoint;
use App\Repositories\Contracts\InterestPointContract;

class InterestPointRepository implements InterestPointContract
{
    /**
     * Create interest point on database using eloquent magic methods.
     *
     * @param InterestPoint $interestPoint Instance of InterestPoint.
     * @return boolean
     */
    public function create(InterestPoint $interestPoint): bool
    {
        return $interestPoint->save();
    }
}
