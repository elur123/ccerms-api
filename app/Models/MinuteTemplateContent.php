<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinuteTemplateContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'order',
        'minute_template_id'
    ];

    /** 
     * 
     * Relationship functions
     */

    public function minuteTemplate()
    {
        return $this->belongsTo(MinuteTemplate::class, 'minute_template_id');
    }
}
