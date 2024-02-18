<?php

namespace App\Exports;

use App\Models\teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;

class TeachersExport implements FromCollection, WithHeadings
{
    protected $teachers;

    public function __construct($teachers)
    {
        $this->teachers = $teachers;
    }

    public function collection()
    {
        // Exclude 'id' column from each row
        $filteredteachers = $this->teachers->map(function ($teacher) {
            return collect($teacher)->except(['id'])->all();
        });

        return $filteredteachers;
    }

    public function headings(): array
    {
        // Get column names dynamically from the database
        $columns = Schema::getColumnListing('teachers'); // Adjust the table name as needed

        // Exclude the 'id' column
        $columns = array_diff($columns, ['id']);

        // You can customize the column names if needed
        return $columns;
    }
}
