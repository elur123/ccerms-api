<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefenseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'label'
    ];

    public $timestamps = false;

    /**
     * 
     * Relationship functions
     */

    public function minuteTemplate()
    {
        return $this->hasMany(MinuteTemplate::class, 'defense_type_id');
    }
}
