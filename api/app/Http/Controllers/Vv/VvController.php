<?php

namespace App\Http\Controllers\Vv;

use App\Http\Controllers\Controller;
use App\Services\VvService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VvController extends Controller
{
    protected VvService $vvService;

    public function __construct(VvService $vvService)
    {
        //TODO enable middeleware
//        $this->middleware(VvTokenMiddleware::class);

        $this->vvService = $vvService;
    }

    /**
     * @throws ValidationException
     */
    public function postback(Request $request)
    {
        $this->vvService->savePostBackData($request);
    }

    /** Example register move to graphql
     * @throws GuzzleException
     */
    public function register()
    {
        $this->vvService->registerCompany(3);
    }

    /** Example getLink move to graphql
     * @throws GuzzleException
     */
    public function getLink()
    {
        echo $this->vvService->getLink(3, 'detection');
        exit();
    }
}
