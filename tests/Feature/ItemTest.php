<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function test_addItem_invalidData(): void
    {
        $response = $this->postJson('/api/items', [
            'name' => ['this', 'isnt', 'the', 'correct', 'datatype'],
            'description' => 1324,
            'volume' => 'string'
        ]);
        $response->assertStatus(422)
        ->assertInvalid(['name', 'description', 'volume']);
    }

    public function test_addItem_validInDb()
    {
        $response = $this->postJson('api/items', [
            'name' => "normal name",
            'description' => 'this is a description of an item',
            'volume' => 122323
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('items',[
            'name' => "normal name",
            'description' => 'this is a description of an item',
            'volume' => 122323
        ]);

    }

    public function test_addItem_valid_response()
    {
        $response = $this->postJson('api/items', [
            'name' => "normal name",
            'description' => 'this is a description of an item',
            'volume' => 122323
        ]);
        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'data'])
            ->has('data', function (AssertableJson $json) {
                $json->hasAll(['insertedId'])
                ->whereAllType([
                    'insertedId' => 'integer'
                ])
                ->where('insertedId', 1);
            });
        });
    }

    public function test_getAllItems_success()
    {   
        Item::factory()->create();
        $response = $this->getJson('/api/items');

        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'data'])
            ->has('data',1, function (AssertableJson $json) {
                $json->hasAll([
                    'id',
                    'name',
                    'description',
                    'volume'
                ])
                ->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'description' => 'string',
                    'volume' => 'integer'
                ]);
            });
        });
    }

}
