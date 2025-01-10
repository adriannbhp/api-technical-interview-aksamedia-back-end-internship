<?php

namespace App\Http\Controllers\DTOs\Response;

use Illuminate\Http\Resources\Json\JsonResource;

class Response extends JsonResource
{
    protected $status;
    protected $message;
    protected $data;

    public function __construct($status, $message, $data = [])
    {
        $this->status = $status; // Use 'status' instead of 'success'
        $this->message = $message;
        $this->data = $data;
    }

    public function toArray($request)
    {
        return [
            'status' => $this->status, // Change 'success' to 'status'
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
