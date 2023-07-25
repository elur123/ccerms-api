<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'prepared_by',
        'template_id',
        'group_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Minute $minute) {
           $minute->prepared_by = request()->user()->id;
        });
    }


    /**
     * 
     * Relationship functions
     */

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function userPrepared()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function template()
    {
        return $this->belongsTo(MinuteTemplate::class, 'template_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function contents()
    {
        return $this->hasMany(MinuteContent::class, 'minute_id');
    }
}
