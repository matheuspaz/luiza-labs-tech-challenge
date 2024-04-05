<?php

namespace App\Services;

use App\Enums\InterestPointStatusEnum;
use App\Models\InterestPoint;
use App\Repositories\InterestPointRepository;
use Illuminate\Support\Collection;

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
        $hasRegister = InterestPoint::where('x', $interestPoint->x)->where('y', $interestPoint->y)->exists();

        if ($hasRegister)
            return false;

        return $this->interestRepository->create(interestPoint: $interestPoint);
    }

    /**
     * Verify if interest point exists.
     *
     * @param InterestPoint $interestPoint Interest point
     * @return boolean
     */
    public function exists(InterestPoint $interestPoint): bool
    {
        return $this->interestRepository->exists($interestPoint);
    }

    /**
     * Get a list of interest points.
     *
     * You can do a filter if your pass array of filter.
     * Expect array with this filter togheter: [y, x, mts, hr].
     *
     * @param array $filters Interest points list.
     * @return Collection of interest points or empty array.
     */
    public function list(array $filters): Collection
    {
        $interestPoints = $this->interestRepository->list(filters: $filters);

        return $interestPoints->map(function ($interestPoint) use ($filters) {
            $point = ['name' => $interestPoint->name, 'status' => InterestPointStatusEnum::Fechado];

            $hour = isset($filters['hr']) ? $filters['hr'] : now()->format('H:i:s');
            $openedHour = date('H:i:s', strtotime($interestPoint->opened));
            $closedHour = date('H:i:s', strtotime($interestPoint->closed));
            $filterHour = date('H:i:s', strtotime($hour));

            $inRangeOpen = ($openedHour <= $filterHour && $closedHour >= $filterHour);

            if ($interestPoint->always_open or $inRangeOpen) {
                $point['status'] = InterestPointStatusEnum::Aberto;
            }

            return $point;
        });
    }
}
