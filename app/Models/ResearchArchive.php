<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'title',
        'research_file',
        'file_url',
        'keywords',
        'section_year_from',
        'section_year_to',
        'adviser',
        'course_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (ResearchArchive $archive) {
            // Save archive members
            foreach (json_decode(request()->members) as $key => $value) {
                $archive->members()->create([
                    'name' => $value->name,
                ]);
            }
        });
    }

    /**
     * 
     * Relationship functions
     */

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function members()
    {
        return $this->hasMany(ResearchArchiveMember::class, 'research_archive_id');
    }

    /**
     * 
     * Scope functions
     */
    public function scopeFilter($query, $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        $attributes = [
            'research_archives.group_name',
            'research_archives.title',
            'research_archives.keywords'
        ];

        $attributes = implode(', ', $attributes);

        return $query->whereRaw("
            (CONCAT_WS(' ', {$attributes}) like '%{$keyword}%')
        ");
    }
}
