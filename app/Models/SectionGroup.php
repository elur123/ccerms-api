<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'group_id'
    ];

    public $timestamps = false;

    /**
     * 
     * Relationship functions
     */

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
