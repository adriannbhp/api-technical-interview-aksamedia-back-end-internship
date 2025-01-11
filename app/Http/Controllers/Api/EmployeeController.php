<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DTOs\Request\StoreEmployeeRequest;
use App\Http\Controllers\DTOs\Response\DefaultResponse;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use App\Constants\ResponseMessages;
use App\Constants\HttpStatusCodes;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Fetch employees with optional filters and pagination
    public function index(Request $request): JsonResponse
    {
        $query = Employee::with('division');

        // Apply filters if provided
        $query->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->filled('division_id'), fn($q) => $q->where('division_id', $request->division_id));

        // Paginate results
        $employees = $query->paginate(10);

        if ($employees->isEmpty()) {
            return response()->json(new DefaultResponse('error', ResponseMessages::ERROR_NO_EMPLOYEES_FOUND), HttpStatusCodes::NOT_FOUND);
        }

        // Return success response with pagination data
        return response()->json(new DefaultResponse('success', ResponseMessages::SUCCESS_EMPLOYEES_RETRIEVED,
            EmployeeResource::collection($employees), [
                'pagination' => $employees->toArray(),
            ]), HttpStatusCodes::OK);
    }

    // Store a new employee
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        // Validate and create employee
        $employee = Employee::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => ResponseMessages::SUCCESS_EMPLOYEE_CREATED,
            'data' => new EmployeeResource($employee),
        ], HttpStatusCodes::CREATED);
    }

    // Update an existing employee
    public function update(StoreEmployeeRequest $request, $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => ResponseMessages::SUCCESS_EMPLOYEE_UPDATED,
            'data' => new EmployeeResource($employee),
        ], HttpStatusCodes::OK);
    }

    public function destroy($id): JsonResponse
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => ResponseMessages::SUCCESS_EMPLOYEE_DELETED,
            ], HttpStatusCodes::OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => ResponseMessages::ERROR_FAILED_TO_DELETE . ' ' . $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
