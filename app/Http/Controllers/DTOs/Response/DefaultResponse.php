<?php

namespace App\Http\Controllers\DTOs\Response;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResponse extends JsonResource
{
    protected $status;
    protected $message;
    protected $data;
    protected $pagination;

    public function __construct($status, $message, $data = [], $pagination = null)
    {
        $this->status = $status; // Use 'status' instead of 'success'
        $this->message = $message;
        $this->data = $data;
        $this->pagination = $pagination;
    }

    public function toArray($request)
    {
        return [
            'status' => $this->status, // Change 'success' to 'status'
            'message' => $this->message,
            'data' => $this->data,
            'pagination' => $this->pagination,
        ];
    }
}
