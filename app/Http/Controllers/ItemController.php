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
}
