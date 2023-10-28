<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinuteTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'defense_type_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (MinuteTemplate $minutetemplate) {

            foreach (json_decode(request()->contents ) as $key => $value) {
                $minutetemplate->contents()->create([
                    'label' => $value->label,
                    'order' => $value->order
                ]);
            }
        });
    }

    /**
     * 
     * Relationship functions
     */
    public function type()
    {
        return $this->belongsTo(DefenseType::class, 'defense_type_id');
    }

    public function contents()
    {
        return $this->hasMany(MinuteTemplateContent::class, 'minute_template_id');
    }
}
