<?php
namespace App\Jobs;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class UploadingAutoGeneratedStudentList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $CompanyId;

    public function __construct($filePath, $CompanyId)
    {
        $this->filePath = $filePath;
        $this->CompanyId = $CompanyId;
    }

    public function handle()
    {
        $rows = Excel::toArray([], $this->filePath, null, \Maatwebsite\Excel\Excel::XLSX);
    
        if (count($rows) > 0) {
            $headers = $rows[0][0]; // Assuming the headers are in the first row
    
            for ($i = 1; $i < count($rows[0]); $i++) {
                $row = $rows[0][$i];
    
                // Check if StudentId is defined in the file
                $studentId = isset($row[0]) ? $row[0] : null;
    
                // Check if StudentId already exists
                $existingStudent = Student::where('StudentId', $studentId)->where('CompanyId', $this->CompanyId)->first();
    
                if ($existingStudent) {
                    continue; // Skip if the StudentId already exists
                }
    
                $s = new Student();
    
                // Assign values based on headers
                $s->CompanyId = $this->CompanyId;
    
                // Generate StudentId if not defined in the file
                $s->StudentId = $studentId ? $studentId : strval(10000 + $s->id);
    
                foreach ($headers as $index => $header) {
                    $s->{$header} = $row[$index];
                }
    
                $s->Role = "Student";
    
                $s->save();
            }
        }
    }
}
