<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Requisites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;

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
            $entity_type = $request->post('entity_type');
            $author_id = $request->post('author_id');
            $filepath = $author_id.'/'.$entity_type;
            $store = $file->store($filepath, 's3');
            $filename = explode('/', $store);
            $fileDb = Files::create([
                'file_name' => $original_name,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'entity_type' => $entity_type,
                'author_id' => $author_id,
                'storage_path' => '/'.$filepath.'/',
                'storage_name' => $filename[2],
                'link' => 'https://dev.storage.docudots.com/'.$filepath.'/'.$filename[2],
            ]);
            $exists = Storage::disk('s3')->exists($filepath.'/'.$filename[2]);
            ($exists and $fileDb) ? $link = 'https://dev.storage.docudots.com/'.$filepath.'/'.$filename[2].'' : Storage::disk('s3')->delete($filepath.'/'.$filename[2]);

            return response()->json([$fileDb], 201, [],  JSON_UNESCAPED_SLASHES);
        }
    }

    public function createPDF()
    {
        $html = Requisites::PDFTable('18');
        $pdf = PDF::loadHTML($html);

        return $pdf->download('requisites.pdf');
    }

    public function sendreq(Request $request)
    {
        $html = Requisites::PDFTable('18');
        $email = $request->get('email');
        $pdf = PDF::loadHTML($html);

        Mail::send('mail', [], function ($message) use ($email, $pdf) {
            $message->to($email)->subject('Requisites for Bank from docudots');
            $message->attach($pdf->download('requisites.pdf'));
        });

        return response()->json(['message' => 'Requisites has been send to '], 220);
    }

}
