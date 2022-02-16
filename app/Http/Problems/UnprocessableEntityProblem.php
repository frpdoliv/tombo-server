<?php

namespace App\Http\Problems;

use Illuminate\Http\Request;

class UnprocessableEntityProblem extends HTTPProblem {
    public static int $status = 422;

    private array $errors;

    public function __construct(Request $request, String $detail, array $errors)
    {
        //TODO: implement error type
        parent::__construct('NOT_IMPLEMENTED', __('Unprocessable Entity'), UnprocessableEntityProblem::$status, $detail, $request->path());
        $this->errors = $errors;
    }

    public function toArray(): array
    {
        $responseArray = parent::toArray();
        $responseArray['errors'] = $this->errors;
        return $responseArray;
    }
}
