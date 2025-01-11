<?php

namespace App\Http\Controllers\DTOs\Response;

class LogoutResponse
{
    public $status;
    public $message;

    public function __construct(string $status, string $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function toArray()
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
