<?php
namespace Tests\Unit\Services;

use App\Services\EventFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class EventFilterTest extends TestCase
{
    public function testApplyNoFilter()
    {
        $query = Mockery::mock(Builder::class);
        $request = new Request();

        // Mock the 'when' method to handle both true and false cases
        $query->shouldReceive('when')
            ->with(false, Mockery::any())
            ->andReturnSelf()
            ->once();
        
        $query->shouldReceive('when')
            ->with(false, Mockery::any())
            ->andReturnSelf()
            ->once();

        $eventFilter = new EventFilter();
        $filteredQuery = $eventFilter->apply($query, $request);

        $this->assertSame($query, $filteredQuery);
    }

    public function testApplyDateFilter()
    {
        $query = Mockery::mock(Builder::class);
        $request = new Request(['searchByDate' => '2024-06-01 to 2024-06-30']);

        // Mock the 'when' method for both true and false cases
        $query->shouldReceive('when')
            ->with(true, Mockery::any())
            ->andReturnUsing(function ($condition, $callback) use ($query) {
                return $callback($query);
            })
            ->once();
        
        $query->shouldReceive('when')
            ->with(false, Mockery::any())
            ->andReturnSelf()
            ->once();

        $query->shouldReceive('whereBetween')
            ->once()
            ->with('start_datetime', ['2024-06-01', '2024-06-30'])
            ->andReturnSelf();

        $eventFilter = new EventFilter();
        $filteredQuery = $eventFilter->apply($query, $request);

        $this->assertSame($query, $filteredQuery);
    }

    public function testApplyCountryFilter()
    {
        $query = Mockery::mock(Builder::class);
        $request = new Request(['searchByCountry' => 'United States']);

        // Mock the 'when' method for both true and false cases
        $query->shouldReceive('when')
            ->with(false, Mockery::any())
            ->andReturnSelf()
            ->once();

        $query->shouldReceive('when')
            ->with(true, Mockery::any())
            ->andReturnUsing(function ($condition, $callback) use ($query) {
                return $callback($query);
            })
            ->once();

        $query->shouldReceive('where')
            ->once()
            ->with('country', 'like', '%United States%')
            ->andReturnSelf();

        $eventFilter = new EventFilter();
        $filteredQuery = $eventFilter->apply($query, $request);

        $this->assertSame($query, $filteredQuery);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
