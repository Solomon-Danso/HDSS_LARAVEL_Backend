<?php

namespace App\Exports;

use App\Models\StaffMembers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;

class StaffMemberssExport implements FromCollection, WithHeadings
{
    protected $StaffMemberss;

    public function __construct($StaffMemberss)
    {
        $this->StaffMemberss = $StaffMemberss;
    }

    public function collection()
    {
        // Exclude 'id' column from each row
        $filteredStaffMemberss = $this->StaffMemberss->map(function ($StaffMembers) {
            return collect($StaffMembers)->except(['id'])->all();
        });

        return $filteredStaffMemberss;
    }

    public function headings(): array
    {
        // Get column names dynamically from the database
        $columns = Schema::getColumnListing('staff_members'); // Adjust the table name as needed

        // Exclude the 'id' column
        $columns = array_diff($columns, ['id']);

        // You can customize the column names if needed
        return $columns;
    }
}
