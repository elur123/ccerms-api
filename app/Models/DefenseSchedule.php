<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusEnum;

class DefenseSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue',
        'start_at',
        'end_at',
        'schedule_at',
        'type_id',
        'group_id',
        'status_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (DefenseSchedule $defense) {
            $defense->status_id = StatusEnum::PENDING->value;
        });
    }

    /**
     * 
     * Relationship functions
     */

    public function type()
    {
        return $this->belongsTo(DefenseType::class, 'type_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function panels()
    {
        return $this->hasMany(DefensePanel::class, 'defense_schedule_id');
    }

    public function minute()
    {
        return $this->hasOne(Minute::class, 'schedule_id')->latestOfMany();
    }
}
