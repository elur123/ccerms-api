<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefensePanel extends Model
{
    use HasFactory;

    protected $fillable = [
        'defense_schedule_id',
        'name'
    ];

    public $timestamps = false;

    /**
     * 
     * Relationship functions
     */

    public function defenseSchedule()
    {
        return $this->belongsTo(DefenseSchedule::class, 'defense_schedule_id');
    }
}
