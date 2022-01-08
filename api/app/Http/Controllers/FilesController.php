<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $filename = time().'.'.$file->getClientOriginalExtension();
        //$storage = Storage::disk('public');
        $filepath = '/test/'.$filename;
        Storage::put($filepath, file_get_contents($file));
        return response()->json(['status' => true, 'data' => $filename], 201);
        //return $filename;
    }

}
