<?php

namespace Tests\Feature;

use App\Models\Unit;
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

    public function test_add_invalidDate():void
    {
        $response = $this->postJson('/api/units', [
            'name' => 1,2,3,4,
            'description' => '',
            'volume' => 'this isnt a int',
            'available' => []
        ]);

        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume', 'available']);
    
    }

    public function test_add_valid():void
    {   
        
        $response = $this->postJson('/api/units', [
            'name' => 'this name',
            'description' => 'this is a description',
            'volume' => 30540,
            'available' => true
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('units',[
            'name' => 'this name',
            'description' => 'this is a description',
            'volume' => 30540,
            'available' => true
        ]);
    }
}
