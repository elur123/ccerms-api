<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'capstone_type_id',
    ];


    /**
     * 
     * Relationship functions
     */

    public function capstoneType()
    {
        return $this->belongsTo(CapstoneType::class, 'capstone_type_id');
    }

    public function milestoneList()
    {
        return $this->hasMany(MilestoneList::class, 'milestone_id');
    }
}
