<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitController extends Controller
{
   public function add(Request $request)
   {
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'volume' => 'required',
        'available' => 'required'
    ]);
}
}
