<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_groups_returns_list_of_groups(): void
    {
        Group::factory()->create(['name' => 'Group 1', 'sort' => 1]);
        Group::factory()->create(['name' => 'Group 2', 'sort' => 2]);

        $response = $this->getJson('/api/group');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['name' => 'Group 1', 'sort' => 1],
                ['name' => 'Group 2', 'sort' => 2],
            ]);
    }

    public function test_get_groups_returns_empty_list_when_no_groups(): void
    {
        $response = $this->getJson('/api/group');

        $response->assertStatus(200)
            ->assertJson([]);
    }
}
