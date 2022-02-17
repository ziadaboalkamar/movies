<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable=['eid','banner','title','description' ,'poster','release_date','vote','vote_count'];

    public function genres(){
        return $this->belongsToMany(Genre::class,'movie_genre');
    }
}
