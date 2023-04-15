<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    protected $fillable = ["title", "slug", "episodes", "status", "type", "aired_from", "aired_to", "season", "studio", "genres", "descriptions", "images", "images_square"];
}
