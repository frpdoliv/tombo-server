<?php

namespace App\Http\Problems;

use Illuminate\Http\Request;
use App\Http\Problems\HTTPProblem;

class NotFoundProblem extends HTTPProblem {
    public static int $status = 404;

    public function __construct(Request $request, String $detail)
    {
        //TODO: implement error type
        parent::__construct('NOT_IMPLEMENTED', __('Not Found'), NotFoundProblem::$status, $detail, $request->path());
    }
}
