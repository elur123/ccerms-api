<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsEnumArrayObject;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusEnum;
class SectionStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'section_id',
        'user_id'
    ];

    public $timestamps = false;

    // protected $casts = [
    //     'status_id' => StatusEnum::class,
    // ];

    /**
     * 
     * Relationship functions
     */

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
