<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardSubmission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'board_id',
        'student_id',
        'status_id',
        'revision',
        'details',
        'file',
        'file_url',
        'progress',
    ];

    /**
     * 
     * Relationship functions
     */

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function comments()
    {
        return $this->hasMany(BoardComment::class, 'submission_id');
    }
}
