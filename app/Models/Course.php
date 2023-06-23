<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'milestone_one',
        'milestone_two',
    ];

    /**
     * 
     * Relationship functions
     */

    public function milestoneOne()
    {
        return $this->belongsTo(Milestone::class, 'milestone_one');
    }

    public function milestoneTwo()
    {
        return $this->belongsTo(Milestone::class, 'milestone_two');
    }
}
