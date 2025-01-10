<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Define the list of division UUIDs
        $divisionIds = [
            '00b6178e-6f83-4426-a4f2-a3bcc3630754',
            '3c132ea7-1bba-447f-b26a-f4f322470e80',
            '716af168-e6a0-484c-9046-4e4bcc4bc84d',
            '763aed18-6c4c-4027-8c92-28b711d9f006',
            '895ec552-8c88-4358-9631-fb3bb90f6fba',
            'e5672a9a-cbfd-408d-8dcb-346e5a238489',
        ];

        // Example employee data
        $employees = [
            [
                'uuid' => (string) Str::uuid(),
                'image' => 'images/employees/default.png', // Replace with the appropriate image path
                'name' => 'John Doe',
                'phone' => '081234567890',
                'division_id' => $divisionIds[0], // Use the first UUID from the list
                'position' => 'Software Engineer',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'image' => 'images/employees/default.png',
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'division_id' => $divisionIds[1], // Use the second UUID from the list
                'position' => 'Project Manager',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'image' => 'images/employees/default.png',
                'name' => 'Alice Johnson',
                'phone' => '081234567892',
                'division_id' => $divisionIds[2], // Use the third UUID from the list
                'position' => 'UI/UX Designer',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'image' => 'images/employees/default.png',
                'name' => 'Bob Brown',
                'phone' => '081234567893',
                'division_id' => $divisionIds[3], // Use the fourth UUID from the list
                'position' => 'Data Analyst',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'image' => 'images/employees/default.png',
                'name' => 'Charlie Davis',
                'phone' => '081234567894',
                'division_id' => $divisionIds[4], // Use the fifth UUID from the list
                'position' => 'DevOps Engineer',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'image' => 'images/employees/default.png',
                'name' => 'Diana Evans',
                'phone' => '081234567895',
                'division_id' => $divisionIds[5], // Use the sixth UUID from the list
                'position' => 'Quality Assurance',
            ],
            // Add more employees as needed, ensuring to use the division IDs as required
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
