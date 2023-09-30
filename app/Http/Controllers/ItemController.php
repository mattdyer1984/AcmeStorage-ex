<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function addItem(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:100',
            'description' => 'required|string|max:500',
            'volume' => 'required|integer|min:0'
        ]);

        $item = new Item();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->volume = $request->volume;

        $item->save();

        return response()->json([
            'data' => [
                'insertedId' => $item->id
            ],
            'message' => 'success'
        ]);

    }

    public function getAllItems()
    {
        return response()->json([
            'data' => Item::all()->makeHidden(['created_at', 'updated_at']),
            'message' => 'success'
        ]);
    }

    public function updateItem(int $id, Request $request) 
    {
        $request->validate([
            'id' => 'required|integer|exists:items,id',
            'name' => 'required|string|min:1|max:100',
            'description' => 'required|string|min:4|max:500', 
            'volume' => 'required|integer|min:1',
            
        ]);

        $name = $request->name;
        $description = $request->description;
        $volume = $request->volume;
        

        $item_to_update = Item::find($id);

        if($name) {
            $item_to_update->name = $name;
        }

        if($description) {
            $item_to_update->description = $description;
        }

        if($volume) {
            $item_to_update->volume = $volume;
        }

        if($item_to_update->save()) {
            return response()->json([
                'data' => [
                    'updatedId' => $id
                ],
                'message' => 'success'
            ]);
        } return response()->json([
            'data' => [],
            'message' => 'There was a problem'
        ]);
    }
}
