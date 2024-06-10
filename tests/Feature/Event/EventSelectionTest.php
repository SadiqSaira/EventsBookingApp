<?php
namespace Tests\Feature\Event;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class EventSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_selected_event_details(): void
    {
        //create five events
        Event::factory()->create(['id' => 1]);
        Event::factory()->create(['id' => 2]);
        Event::factory()->create(['id' => 3]);
        Event::factory()->create(['id' => 4]);
        Event::factory()->create(['id' => 5]);
        
        $response =$this->get(route('bookevent.index', ['eventId' => '3']))
        ->assertStatus(200);

    }

}
