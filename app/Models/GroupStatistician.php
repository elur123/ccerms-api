<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupStatistician extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id'
    ];

    public $timestamps = false;
}
