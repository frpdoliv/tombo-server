<?php

namespace App\Http\Problems;

class HTTPProblem {
    private $data;

    public function __construct(String $type, String $title, int $status, String $detail, String $instance)
    {
        $this->data = [
            'type' => $type,
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => $instance
        ];
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
