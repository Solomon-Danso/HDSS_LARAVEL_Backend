<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;

class StudentsExport implements FromCollection, WithHeadings
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        // Exclude 'id' column from each row
        $filteredStudents = $this->students->map(function ($student) {
            return collect($student)->except(['id'])->all();
        });

        return $filteredStudents;
    }

    public function headings(): array
    {
        // Get column names dynamically from the database
        $columns = Schema::getColumnListing('students'); // Adjust the table name as needed

        // Exclude the 'id' column
        $columns = array_diff($columns, ['id']);

        // You can customize the column names if needed
        return $columns;
    }
}
