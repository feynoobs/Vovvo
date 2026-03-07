<?php

namespace Tests\Feature\Feature;

use App\Models\Board;
use App\Models\Group;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_threads_for_board_returns_list_of_threads(): void
    {
        $group = Group::factory()->create();
        $board = Board::factory()->forGroup($group->id)->create(['name' => 'Board 1', 'sort' => 1]);
        Thread::factory()->forBoard($board->id)->create(['name' => 'Thread 1', 'sort' => 1]);
        Thread::factory()->forBoard($board->id)->create(['name' => 'Thread 2', 'sort' => 2]);

        $response = $this->getJson("/api/board/{$board->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['name' => 'Thread 1', 'sort' => 1],
                ['name' => 'Thread 2', 'sort' => 2],
            ]);
    }

    public function test_get_threads_for_nonexistent_board_returns_empty(): void
    {
        $response = $this->getJson('/api/board/999');

        $response->assertStatus(200)
            ->assertJson([]);
    }
}
