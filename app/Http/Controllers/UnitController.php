<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

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

    public function getUnits(Request $request)
    {
        $available = $request->query('available');

        if($available===null)
        {
           return response()->json([
                'data' => Unit::all()->makeHidden(['created_at', 'updated_at']),
                'message' => 'success'
        ]);
        } else {
            $request->validate(['available' => 'integer|min:0|max:1']);
            $units = Unit::where('available', $available)->get()->makeHidden(['created_at', 'updated_at']);
            
            if (isEmpty($units)){
            return response()->json([
                'data' => $units,
                'message' => 'No matching Units'
        ]);
        } else {return response()->json([
                'data' => $units,
                'message' => 'success'
            
            ]);
        }
        
    }}

    public function updateUnit(int $id, Request $request) 
    {
        $request->validate([
            'id' => 'required|integer|exists:units,id',
            'name' => 'required|string|min:1|max:100',
            'description' => 'required|string|min:4|max:500', 
            'volume' => 'required|integer|min:1',
            'available' => 'required|boolean'
        ]);

        $name = $request->name;
        $description = $request->description;
        $volume = $request->volume;
        $available = $request->available;

        $unit_to_update = Unit::find($id);

        if($name) {
            $unit_to_update->name = $name;
        }

        if($description) {
            $unit_to_update->description = $description;
        }

        if($volume) {
            $unit_to_update->volume = $volume;
        }

        if($available) {
            $unit_to_update->available = $available;
        }

        if($unit_to_update->save()) {
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
