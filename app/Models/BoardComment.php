<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'comment_by',
        'comment',
        'file',
        'file_url',
    ];

    /**
     * 
     * Relationship functions
     */

    public function submission()
    {
        return $this->belongsTo(BoardSubmission::class, 'submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'comment_by');
    }
}
