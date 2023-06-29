<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'percent',
        'milestone_id',
        'order_by',
    ];

    /**
     * 
     * Relationship functions
     */

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }
}
