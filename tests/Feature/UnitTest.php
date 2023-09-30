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

    public function test_getUnits_success()
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

    public function test_getUnitsAvailable_success()
    {   
        Unit::factory()->create([
            'available' => 1
        ]);
        $response = $this->getJson('/api/units?available=1');

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
    public function test_getUnitsUnavailable_success()
    {   
        Unit::factory()->create([
            'available' => 0
        ]);
        $response = $this->getJson('/api/units?available=0');

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

    public function test_getUnitsAvailable_failure()
    {   
        Unit::factory()->create([
            'available' => 0
        ]);
        $response = $this->getJson('/api/units?available=1');

        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'data'])
            ->where('message', 'No matching Units');
        });
    }
    public function test_updateUnit_invalid() 
    {   
        
        $response = $this->putJson("/api/units/1", []);
        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume', 'available']);
    }

    public function test_updateUnit_invalidData() 
    {
        $response = $this->putJson('/api/units/1', [
            'name' => ['horse'],
            'description' => '',
            'volume' => 'string',
            'available' => 9

        ]);
        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume', 'available']);
    }

    public function test_UpdateUnit_validInDb()
    {
        $unit = Unit::factory()->create();

        $response = $this->putJson("api/units/{$unit->id}", [
            'id' => $unit->id,
            'name' => 'newName',
            'description' => 'newDescription',
            'volume' => 500,
            'available' => true
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' =>'newName',
            'description' => 'newDescription',
            'volume' => 500,
            'available' => 1
        ]);

    }
}
