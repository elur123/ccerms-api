<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'step_id',
        'personnel_id',
        'status_id',
        'progress',
        'type'
    ];

    /**
     * 
     * Relationship functions
     */

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function step()
    {
        return $this->belongsTo(MilestoneList::class, 'step_id');
    }

    public function personnel()
    {
        return $this->belongsTo(User::class, 'personnel_id');
    }

    public function submissions()
    {
        return $this->hasMany(BoardSubmission::class, 'board_id');
    }
}
