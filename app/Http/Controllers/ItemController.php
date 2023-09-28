<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function addItem(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'description' => 'required',
            'volume' => 'required'
        ]);
    }
}
