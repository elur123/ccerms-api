<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchArchiveMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_archive_id',
        'name'
    ];

    public $timestamps = false;

    /**
     * 
     * Relationship functions
     */

    public function researchArchives()
    {
        return $this->belongsTo(ResearchArchive::class, 'research_archive_id');
    }
}
