<?php

namespace App\Http\Controllers\Vv;

use App\DTO\TransformerDTO;
use App\DTO\Vv\VvPostBackResponse;
use App\Http\Controllers\Controller;
use App\Http\Middleware\VvTokenMiddleware;
use App\Services\VvService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class VvController extends Controller
{
    protected VvService $vvService;

    public function __construct(VvService $vvService)
    {
        $this->middleware(VvTokenMiddleware::class);

        $this->vvService = $vvService;
    }


    /**
     * @throws ValidationException
     */
    public function postback(Request $request)
    {
        $this->vvService->validationPostBack($request);

        $vvDto = TransformerDTO::transform(VvPostBackResponse::class, $request->all(), $request->header('token'));

        $this->vvService->savePostBackData($vvDto);
    }


}
