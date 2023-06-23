<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'label'
    ];

    public $timestamps = false;

    const LABEL = [
        'Admin' => 1,
        'Research Coordinator' => 2,
        'Subject Teacher' => 3,
        'Adviser' => 4,
        'Panel' => 5,
        'Student' => 6
    ];
}
