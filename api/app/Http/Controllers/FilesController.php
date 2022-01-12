<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

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
            //$fileDb[] = ['link' => 'https://dev.storage.docudots.com/'.$filepath.'/'.$filename];

            return response()->json([$fileDb], 201, [],  JSON_UNESCAPED_SLASHES);
        }
    }

    public function createPDF() {
        $data = Files::all();

        $html ='';
        foreach ($data as $post) {
            $html .= '<div class="table-scrollable">
                    <table border=1 id="posts" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody id="body"><tr>
                            <td>'
                . $post->id . ' 
                            </td>
                            <td>' .
                $post->file_name .
                '</td>
                            <td>'
                . $post->mime_type .
                '</td> 
                </tr>
               </tbody>
            </table>
        </div>';
        }

        $pdf = PDF::loadHTML($html);

        return $pdf->download('test.pdf');
    }

}
