<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'milestone_id',
        'milestone_list_id',
    ];
}
