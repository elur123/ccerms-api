<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefenseVenue extends Model
{
    use HasFactory;

    protected $fillable = ['label'];

    public $timestamps = false;
}
