<?php

namespace App\Console\Commands;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this is to get all the movies from the Tmdb';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
$this->GetPopulerMovies();

    }

    private function GetPopulerMovies(){
        $response = Http::get(config('services.tmdb.base_url').'/movie/popular?region=us&api_key='.config('services.tmdb.api_key'));
        foreach ($response->json()['results'] as $result){

        $movie = Movie::create([
            'eid'=>$result['id'],
            'banner'=>$result['backdrop_path'],
            'title'=>$result['title'],
            'description'=>$result['overview'],
            'poster'=>$result['poster_path'],
            'release_date'=>$result['release_date'],
            'vote'=>$result['vote_average'],
            'vote_count'=>$result['vote_count'],
        ]);
        foreach ($result['genre_ids'] as $genreId){
            $genre = Genre::where('e_id',$genreId)->first();
            $movie->genres()->attach($genre->id);
        }
        }
    }
}
