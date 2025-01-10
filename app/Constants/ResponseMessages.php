<?php

namespace App\Constants;

class ResponseMessages
{
    const ERROR_NO_EMPLOYEES_FOUND = 'No employees found';
    const SUCCESS_EMPLOYEES_RETRIEVED = 'Employees retrieved successfully';
    const ERROR_FIELD_REQUIRED = ' field is required.';
    const SUCCESS_EMPLOYEE_CREATED = 'Employee created successfully.';
    const SUCCESS_EMPLOYEE_UPDATED = 'Employee updated successfully.';
    const SUCCESS_EMPLOYEE_DELETED = 'Employee deleted successfully.';
    const ERROR_FAILED_TO_DELETE = 'Failed to delete employee: ';
    const ERROR_NO_DIVISIONS_FOUND = 'No divisions found';
    const SUCCESS_DIVISIONS_RETRIEVED = 'Divisions retrieved successfully';
    const ERROR_INVALID_CREDENTIALS = 'Invalid username or password'; // Added for login
    const SUCCESS_LOGIN = 'Successfully logged in'; // Added for login
    const SUCCESS_LOGOUT = 'Logout successful!'; // Added for logout
    const ERROR_LOGOUT_FAILED = 'Logout failed!'; // Added for logout
    const SUCCESS_USER_CREATED = 'User created successfully'; // Added for user creation
}
