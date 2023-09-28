<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
   public function addUnit(Request $request)
   {
    $request->validate([
        'name' => 'required|string|max:100',
        'description' => 'required|string|max:500',
        'volume' => 'required|integer|min:0',
        'available' => 'required|boolean'
    ]);

    $unit = new Unit();
        $unit->name = $request->name;
        $unit->description = $request->description;
        $unit->volume = $request->volume;
        $unit->available = $request->available;

        $unit->save();

        return response()->json([
            'data' => [
                'insertedId' => $unit->id
            ],
            'message' => 'success'
        ]);
}
}
