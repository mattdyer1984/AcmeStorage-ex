<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_add_invalid(): void
    {
        $response = $this->postJson('/api/units', []);

        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume', 'available']);
    }
}
