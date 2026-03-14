<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Group;
use App\Models\Response;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_response_creates_new_response(): void
    {
        $group = Group::factory()->create();
        $board = Board::factory()->forGroup($group->id)->create();
        $thread = Thread::factory()->forBoard($board->id)->create();

        $data = [
            'thread_id' => $thread->id,
            'content' => 'This is a test response.',
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertStatus(201)
            ->assertJson([
                'thread_id' => $thread->id,
                'content' => 'This is a test response.',
                'sort' => 1,
            ]);

        $this->assertDatabaseHas('responses', $data + ['sort' => 1]);
    }

    public function test_post_response_with_invalid_data_returns_validation_error(): void
    {
        $data = [
            'thread_id' => 999, // Nonexistent
            'content' => '',
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['thread_id', 'content']);
    }

    public function test_post_response_assigns_correct_sort_number(): void
    {
        $group = Group::factory()->create();
        $board = Board::factory()->forGroup($group->id)->create();
        $thread = Thread::factory()->forBoard($board->id)->create();
        Response::factory()->forThread($thread->id)->create(['sort' => 1]);
        Response::factory()->forThread($thread->id)->create(['sort' => 2]);

        $data = [
            'thread_id' => $thread->id,
            'content' => 'New response.',
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertStatus(201)
            ->assertJson([
                'sort' => 3,
            ]);
    }
}
