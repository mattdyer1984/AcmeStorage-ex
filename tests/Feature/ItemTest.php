<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    public function test_addItem_invalid(): void
    {
        $response = $this->postJson('/api/items', []);

        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume']);
    }
}
