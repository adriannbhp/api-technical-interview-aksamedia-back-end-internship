<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DTOs\Request\DivisionRequest;
use App\Http\Controllers\DTOs\Response\DefaultResponse;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
use App\Constants\ResponseMessages;
use App\Constants\HttpStatusCodes;

class DivisionController extends Controller
{
    public function index(DivisionRequest $request): JsonResponse
    {
        // Query to get divisions with optional filter by name
        $query = Division::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Paginate results
        $divisions = $query->paginate(10);

        // If no divisions found, return error response
        if ($divisions->isEmpty()) {
            return response()->json(new DefaultResponse(
                'error',
                ResponseMessages::ERROR_NO_DIVISIONS_FOUND,
                [],
                null
            ), HttpStatusCodes::NOT_FOUND);
        }

        // Prepare pagination data
        $paginationData = [
            'current_page' => $divisions->currentPage(),
            'last_page' => $divisions->lastPage(),
            'per_page' => $divisions->perPage(),
            'total' => $divisions->total(),
            'next_page_url' => $divisions->nextPageUrl(),
            'prev_page_url' => $divisions->previousPageUrl(),
        ];

        // Prepare response data with division info
        $responseData = [
            'divisions' => $divisions->map(fn ($division) => [
                'id' => $division->id,
                'name' => $division->name,
            ]),
        ];

        // Return success response with pagination data
        return response()->json(new DefaultResponse(
            'success',
            ResponseMessages::SUCCESS_DIVISIONS_RETRIEVED,
            $responseData,
            $paginationData
        ), HttpStatusCodes::OK);
    }
}
