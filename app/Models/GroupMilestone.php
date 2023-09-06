<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'progress',
        'group_id',
        'milestone_id',
        'milestone_list_id',
    ];

    /**
     * 
     * Relationship functions
     */

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function currentMilestone()
    {
        return $this->belongsTo(MilestoneList::class, 'milestone_list_id');
    }
}
