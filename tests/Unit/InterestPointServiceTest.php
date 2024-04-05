<?php

namespace Tests\Unit;

use App\Enums\InterestPointStatusEnum;
use App\Models\InterestPoint;
use App\Services\InterestPointService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InterestPointServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Set up Sqlite with data
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->seed();
    }

    /**
     * Create a interest point test
     */
    public function testCreateInterestPoint(): void
    {
        /**
         * @var InterestPointService $interestPointService
         */
        $interestPointService = app(InterestPointService::class);

        $restaurantPoint = new InterestPoint([
            'name' => 'Restaurante',
            'x' => 1540,
            'y' => 1550,
            'opened' => '12:00:00',
            'closed' => '18:00:00',
        ]);

        $parkPoint = new InterestPoint([
            'name' => 'Praça',
            'x' => 1540,
            'y' => 1550,
            'always_open' => true,
        ]);

        $isSaved = $interestPointService->create($restaurantPoint);
        $this->assertTrue($isSaved, 'Returns true when interest point is successful saved');

        $isSaved = $interestPointService->create($parkPoint);
        $this->assertFalse($isSaved, 'Returns false when interest point is duplicated, preventing saves');
    }

    /**
     * List interest points test
     */
    public function testListInterestPoints(): void
    {
        /**
         * @var InterestPointService $interestPointService
         */
        $interestPointService = app(InterestPointService::class);

        $interestPoints = $interestPointService->list();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $interestPoints);

        $interestPoints->map(function ($point) {
            $this->assertNotEmpty($point['name'], 'Assert if name fulfilled');
            $this->assertIsString($point['name'], 'Assert if name is string');

            $this->assertNotEmpty($point['status'], 'Assert if status fulfilled');
            $this->assertIsString($point['status'], 'Assert if status is string');
        });
    }

    /**
     * List interest points test with filters
     */
    public function testListInterestPointsWithFilters(): void
    {
        /**
         * @var InterestPointService $interestPointService
         */
        $interestPointService = app(InterestPointService::class);

        $restaurantPoint = new InterestPoint([
            'name' => 'Restaurante',
            'x' => 68,
            'y' => 11,
            'opened' => '12:00:00',
            'closed' => '18:00:00',
        ]);

        $parkPoint = new InterestPoint([
            'name' => 'Praça',
            'x' => 90,
            'y' => 10,
            'always_open' => true,
        ]);

        $gasStationPoint = new InterestPoint([
            'name' => 'Posto de combustível',
            'x' => 80,
            'y' => 10,
            'opened' => '13:00:00',
            'closed' => '19:00:00',
        ]);

        $interestPointService->create($restaurantPoint);
        $interestPointService->create($parkPoint);
        $interestPointService->create($gasStationPoint);

        $interestPoints = $interestPointService->list([
            'x' => 70,
            'y' => 10,
            'mts' => 10,
            'hr' => '12:00:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $interestPoints);

        $interestPoints->map(function ($point) {
            $this->assertNotEmpty($point['name'], 'Assert if name fulfilled');
            $this->assertIsString($point['name'], 'Assert if name is string');

            $this->assertNotEmpty($point['status'], 'Assert if status fulfilled');
            $this->assertIsString($point['status'], 'Assert if status is string');
        });

        $hasOpenedPoint = $interestPoints->contains(function ($point) {
            return $point['status'] === InterestPointStatusEnum::Aberto->value;
        });

        $this->assertTrue($hasOpenedPoint, 'Assert if has an opened point in list.');

        $hasClosedPoint = $interestPoints->contains(function ($point) {
            return $point['status'] === InterestPointStatusEnum::Fechado->value;
        });

        $this->assertTrue($hasClosedPoint, 'Assert if has an closed point in list.');
    }

    /**
     * Exists interest point test
     */
    public function testExistsInterestPoint(): void
    {
        /**
         * @var InterestPointService $interestPointService
         */
        $interestPointService = app(InterestPointService::class);

        $restaurantPoint = new InterestPoint([
            'name' => 'Restaurante',
            'x' => 1090,
            'y' => 1760,
            'opened' => '12:00:00',
            'closed' => '18:00:00',
        ]);

        $parkPoint = new InterestPoint([
            'name' => 'Praça',
            'x' => 1110,
            'y' => 1330,
            'always_open' => true,
        ]);

        $interestPointService->create($restaurantPoint);
        $exists = $interestPointService->exists($restaurantPoint);
        $this->assertTrue($exists, 'Returns true when interest point exists');

        $exists = $interestPointService->exists($parkPoint);
        $this->assertFalse($exists, 'Returns false when interest point exists');
    }
}
