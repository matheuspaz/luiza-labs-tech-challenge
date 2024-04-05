<?php

namespace App\Repositories;

use App\Models\InterestPoint;
use App\Repositories\Contracts\InterestPointContract;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Verify interest point on database using eloquent magic methods.
     *
     * @param InterestPoint $interestPoint Instance of InterestPoint.
     * @return boolean
     */
    public function exists(InterestPoint $interestPoint): bool
    {
        return InterestPoint::where('x', $interestPoint->x)
            ->where('y', $interestPoint->y)->exists();
    }

    /**
     * List interest points with filters if available.
     *
     * @param array $filters - [x, y, mts, hr]
     * @return Collection of interest points or empty array
     */
    public function list(array $filters): Collection
    {
        if (count($filters)) {
            // Make DB call with euclidian calculation to match with meters passed in filters
            return InterestPoint::select('name', 'opened', 'closed', 'always_open')
                ->whereRaw('SQRT(POW(x - ?, 2) + POW(y - ?, 2)) <= ?', [$filters['x'], $filters['y'], $filters['mts']])
                ->get();
        }

        return InterestPoint::all('name', 'opened', 'closed', 'always_open');
    }
}
