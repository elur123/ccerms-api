<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'title',
        'course_id',
        'capstone_type_id',
    ];


    /**
     * 
     * Relationship functions
     */

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function capstoneType()
    {
        return $this->belongsTo(CapstoneType::class, 'capstone_type_id');
    }

    public function groupMilestone()
    {
        return $this->hasMany(GroupMilestone::class, 'group_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id');
    }

    public function advisers()
    {
        return $this->belongsToMany(User::class, 'group_advisers', 'group_id', 'user_id');
    }

    public function panels()
    {
        return $this->belongsToMany(User::class, 'group_panels', 'group_id', 'user_id');
    }
}