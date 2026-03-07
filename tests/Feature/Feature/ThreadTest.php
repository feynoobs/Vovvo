<?php

namespace Tests\Feature\Feature;

use App\Models\Board;
use App\Models\Group;
use App\Models\Response;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_responses_for_thread_returns_list_of_responses(): void
    {
        $group = Group::factory()->create();
        $board = Board::factory()->forGroup($group->id)->create();
        $thread = Thread::factory()->forBoard($board->id)->create(['name' => 'Thread 1', 'sort' => 1]);
        Response::factory()->forThread($thread->id)->create(['content' => 'Response 1', 'sort' => 1]);
        Response::factory()->forThread($thread->id)->create(['content' => 'Response 2', 'sort' => 2]);

        $response = $this->getJson("/api/thread/{$thread->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['content' => 'Response 1', 'sort' => 1],
                ['content' => 'Response 2', 'sort' => 2],
            ]);
    }

    public function test_get_responses_for_nonexistent_thread_returns_empty(): void
    {
        $response = $this->getJson('/api/thread/999');

        $response->assertStatus(200)
            ->assertJson([]);
    }
}
