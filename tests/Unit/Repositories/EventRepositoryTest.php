<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\EventRepository;
use App\Models\Event;
use Mockery;
use Illuminate\Database\Eloquent\Builder;

class EventRepositoryTest extends TestCase
{
    protected $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = new EventRepository();
    }

    public function test_apply_date_filter_with_date_range()
    {
        $queryMock = Mockery::mock(Builder::class);

        $startDate = '2024-06-26';
        $endDate = '2024-06-30';
        $dateRange = $startDate . ' to ' . $endDate;

        // Expect whereBetween to be called with the correct parameters
        $queryMock->shouldReceive('whereBetween')
            ->once()
            ->with('start_datetime', [$startDate, $endDate])
            ->andReturnSelf();

        // Call the applyDateFilter method
        $result = $this->eventRepository->applyDateFilter($queryMock, $dateRange);

        // Assert that the result is an instance of the Builder class
        $this->assertInstanceOf(Builder::class, $result);

        $this->assertInstanceOf(Builder::class, $result);

        $queryMock->shouldHaveReceived('whereBetween')
        ->once()
        ->with('start_datetime', [$startDate, $endDate]);

        $this->assertTrue(true); 
    }

    
   

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
