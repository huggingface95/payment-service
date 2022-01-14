<?php

namespace App\Http\Controllers;

use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualLabel;
use App\Models\DepartmentPosition;
use App\Models\Departments;
use App\Models\Members;
use App\Models\Roles;
use function PHPUnit\Framework\isEmpty;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    public function index()
    {
        $departamentPosition = DepartmentPosition::find(3);
        if (!isset($departamentPosition)) {
            echo "error";
        }
        if ($departamentPosition->department->company->id !== 1) {
            echo "error" . $departamentPosition->department->company->id;
        }
        dd($departamentPosition->department->company->id);

    }

    //
}
