<?php

namespace App\Http\Controllers\DTOs\Response;

class NilaiResponse
{
    public $status;
    public $message;
    public $data;

    public function __construct($status, $message, $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function toArray()
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
