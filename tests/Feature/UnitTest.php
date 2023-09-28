<?php

namespace Tests\Feature;

use App\Models\Unit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UnitTest extends TestCase
{
   use DatabaseMigrations;

    public function test_addUnit_invalid(): void
    {
        $response = $this->postJson('/api/units', []);

        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume', 'available']);
    }

    public function test_addUnit_invalidData():void
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

    public function test_addUnit_valid():void
    {   
        $this->withoutExceptionHandling();
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

    public function test_addUnit_validFormat()
    {
        $response = $this->postJson('/api/units', [
            'name' => 'this name',
            'description' => 'this is a description',
            'volume' => 30540,
            'available' => true
        ]);
        $response->assertOk()
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['data', 'message'])
            ->has('data', function (AssertableJson $json) {
                $json->hasAll(['insertedId'])
                ->whereAlltype([
                    'insertedId' => 'integer'
                ])
                ->where('insertedId', 1);
            });
        });

    }

    public function test_getAllUnits_success()
    {   
        Unit::factory()->create();
        $response = $this->getJson('/api/units');

        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'data'])
            ->has('data',1, function (AssertableJson $json) {
                $json->hasAll([
                    'id',
                    'name',
                    'description',
                    'volume',
                    'available'
                ])
                ->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'description' => 'string',
                    'volume' => 'integer',
                    'available' => 'integer'
                ]);
            });
        });
    }
}
