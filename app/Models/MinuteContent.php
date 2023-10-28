<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinuteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'minute_id',
        'label',
        'value'
    ];

    public $timestamps = false;

    /**
     * 
     * Relationship functions
     */

    public function minute()
    {
        return $this->belongsTo(Minute::class, 'minute_id');
    }
}
