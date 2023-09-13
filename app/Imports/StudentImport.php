<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;

class StudentImport implements ToCollection
{

    use Importable;

    public $data;

    public function collection(Collection $rows)
    {
        $this->data = $rows->map(fn($row) => [
            'student_id' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'course_code' => $row[3]
        ]);
    }
}
