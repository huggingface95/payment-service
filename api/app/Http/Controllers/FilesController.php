<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,pdf|max:102400'
        ], $messages = [
            'mimes' => 'Please insert only jpeg, jpg, png, gif, pdf files',
            'max' => 'File should be less than 100 MB'
        ]);
        if($request->hasfile('file')) {
            $file = $request->file('file');
            $original_name = $file->getClientOriginalName();
            $entity_type = 'applicant';
            $author_id = '21';
            $filename = $author_id.'_'.$entity_type.'_'.$original_name;
            $filepath = 'test';
            $store = $file->storeAs($filepath, $filename, 's3');
            $fileDb = Files::create([
                'file_name' => $original_name,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'entity_type' => $entity_type,
                'author_id' => $author_id,
                'storage_path' => Storage::disk('s3')->url($store),
                'storage_name' => $filename
            ]);
            $exists = Storage::disk('s3')->exists($filepath.'/'.$filename);
            $exists ? $link = 'https://dev.storage.docudots.com/'.$filepath.'/'.$filename.'' : $link = '';

            return response()->json(['status' => true, 'link' => $link, 'db_info' => $fileDb, 'store' => $store], 201);
        }
    }

}
