<?php

namespace App\Exports;

use App\Models\Authentic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;

class AuthenticExport implements FromCollection, WithHeadings
{
    protected $Authentics;

    public function __construct($Authentics)
    {
        $this->Authentics = $Authentics;
    }

    public function collection()
    {
        // Exclude 'id' column from each row
        $filteredAuthentics = $this->Authentics->map(function ($Authentic) {
            return collect($Authentic)->except(['id'])->all();
        });

        return $filteredAuthentics;
    }

    public function headings(): array
    {
        // Get column names dynamically from the database
        $columns = Schema::getColumnListing('Authentics'); // Adjust the table name as needed

        // Exclude the 'id' column
        $columns = array_diff($columns, ['id']);

        // You can customize the column names if needed
        return $columns;
    }
}
