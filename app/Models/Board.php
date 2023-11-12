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
     * Scope functions
     */
    public function scopeFilter($query, $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        $attributes = [
            'groups.group_name',
            'milestone_lists.title',
            'users.name'
        ];

        $attributes = implode(', ', $attributes);

        return $query->whereRaw("
            (CONCAT_WS(' ', {$attributes}) like '%{$keyword}%')
        ");
    }

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

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function submissions()
    {
        return $this->hasMany(BoardSubmission::class, 'board_id')->latest();
    }
}
