<?php

namespace App\Http\Problems;

use Illuminate\Http\Request;

class UnauthorizedProblem extends HTTPProblem {
    public static int $status = 401;

    public function __construct(Request $request, String $detail)
    {
        //TODO: implement error type
        parent::__construct('NOT IMPLEMENTED', __('Unauthorized'), UnauthorizedProblem::$status, $detail, $request->path());
    }
}
