<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_code',
        'room_number',
        'time_start_at',
        'time_end_at',
        'year_start_at',
        'year_end_at',
        'start_at',
        'end_at',
        'section_type_id',
        'user_id'
    ]; 

    /**
     * 
     * Relationship functions
     */

    public function sectionType()
    {
        return $this->belongsTo(SectionType::class, 'section_type_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'section_students', 'section_id', 'user_id')->withPivot(['status_id']);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'section_groups', 'section_id', 'group_id');
    }
}
