<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InterestPointTest extends TestCase
{
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
     * Test create interest point full feature
     */
    public function testCreateInterestPoint(): void
    {
        $token = $this->createAuthToken();

        $interestPoint = [
            'name' => 'Restaurante',
            'x' => 1540,
            'y' => 1550,
            'opened' => '12:00:00',
            'closed' => '18:00:00'
        ];

        $response = $this->postJson('/api/interest-points', $interestPoint, [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(201)
            ->assertExactJson($interestPoint);
    }

    /**
     * Test list interest points full feature
     */
    public function testListInterestPoints(): void
    {
        $token = $this->createAuthToken();

        $response = $this->getJson('/api/interest-points', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                [
                    'name',
                    'status'
                ]
            ]);
    }
}
