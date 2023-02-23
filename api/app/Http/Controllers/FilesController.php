<?php

namespace App\Http\Controllers;

use App\Models\Requisites;
use App\Services\FileService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class FilesController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function upload(Request $request)
    {
        $allowedEntityTypes = [
            'member',
            'company',
            'document',
            'applicant_individual',
            'applicant_company',
            'applicant',
            'project',
        ];

        $this->validate($request, [
            'file' => 'required_without:files|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:102400',
            'files' => 'required_without:file',
            'files.*' => 'required_without:file|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:102400',
            'entity_type' => ['required', Rule::in($allowedEntityTypes)],
            'author_id' => ['required', 'integer'],
        ], $messages = [
            'mimes' => 'Please insert only jpeg, jpg, png, gif, pdf files',
            'max' => 'File should be less than 100 MB',
        ]);

        $response = [];
        if ($request->hasfile('file')) {
            $response[] = $this->fileService->uploadFile($request, $request->file('file'));
        }

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $response[] = $this->fileService->uploadFile($request, $file);
            }
        }

        if (empty($response)) {
            return response()->json(['message' => 'No files uploaded'], 400);
        }

        return response()->json($response, 201, [], JSON_UNESCAPED_SLASHES);
    }

    public function createPDF(Request $request)
    {
        $account_id = $request->get('account_id');
        $html = Requisites::PDFTable('15');
        $pdf = PDF::loadHTML($html);

        return $pdf->download('requisites.pdf');
    }

    public function sendreq(Request $request)
    {
        $email = $request->get('email');
        $account_id = $request->get('account_id');
        $html = Requisites::PDFTable('15');
        $pdf = PDF::loadHTML($html);

        Mail::send('mail', [], function ($message) use ($email, $pdf) {
            $message->to($email)->subject('Requisites for Bank from docudots');
            $message->from('acdteam3@gmail.com', 'Docudots');
            $message->attachData($pdf->output(), 'requisites.pdf');
        });

        return response()->json(['message' => 'Requisites has been send to '.$email], 200);
    }
}
