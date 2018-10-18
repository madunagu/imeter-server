<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FOTAController extends Controller
{
    //
    public function saveBIN(Request $request)
    {
        $path = $request->file('imeter')->storeAs(
            'bin',
            'imeter.bin'
        );
        return response()->json(compact($path));
    }

    public function getBIN(Request $request)
    {
        return Storage::download('bin/imeter.bin', 'imeter.bin', []);
    }
}
