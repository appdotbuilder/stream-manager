<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = [
            [
                'title' => 'The Dark Knight',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/hqkIcbrOHL86UncnHIsHVcVmzue.jpg',
                'release_year' => 2008,
                'rating' => 9.0,
                'duration' => 152,
                'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                'video_qualities' => [
                    '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_1mb.mp4',
                    '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                    '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_1mb.mp4',
                ],
                'tmdb_id' => 155,
                'trailer_url' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
                'is_premium' => true,
                'genres' => ['Action', 'Crime', 'Drama', 'Thriller']
            ],
            [
                'title' => 'Inception',
                'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/s3TBrRGB1iav7gFOCNx3H31MoES.jpg',
                'release_year' => 2010,
                'rating' => 8.8,
                'duration' => 148,
                'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4',
                'video_qualities' => [
                    '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_2mb.mp4',
                    '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4',
                    '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_2mb.mp4',
                ],
                'tmdb_id' => 27205,
                'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
                'is_premium' => true,
                'genres' => ['Action', 'Science Fiction', 'Thriller']
            ],
            [
                'title' => 'The Shawshank Redemption',
                'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/iNh3BivHyg5sQRPP1KOkzguEX0H.jpg',
                'release_year' => 1994,
                'rating' => 9.3,
                'duration' => 142,
                'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_5mb.mp4',
                'video_qualities' => [
                    '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_5mb.mp4',
                    '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_5mb.mp4',
                    '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_5mb.mp4',
                ],
                'tmdb_id' => 278,
                'trailer_url' => 'https://www.youtube.com/watch?v=6hB3S9bIaco',
                'is_premium' => false,
                'genres' => ['Drama']
            ],
            [
                'title' => 'Pulp Fiction',
                'description' => 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/4cDFJr4HnXN5AdPw4AKrmLlMWdO.jpg',
                'release_year' => 1994,
                'rating' => 8.9,
                'duration' => 154,
                'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_10mb.mp4',
                'video_qualities' => [
                    '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_10mb.mp4',
                    '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_10mb.mp4',
                    '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_10mb.mp4',
                ],
                'tmdb_id' => 680,
                'trailer_url' => 'https://www.youtube.com/watch?v=s7EdQ4FqbhY',
                'is_premium' => false,
                'genres' => ['Crime', 'Drama']
            ],
            [
                'title' => 'Avatar',
                'description' => 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders and protecting the world he feels is his home.',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/jRXYjXNq0Cs2TcJjLkki24MLp7u.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/Yc9q6QuWrMp9nuDm5R8ExNqbEWU.jpg',
                'release_year' => 2009,
                'rating' => 7.8,
                'duration' => 162,
                'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_20mb.mp4',
                'video_qualities' => [
                    '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_20mb.mp4',
                    '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_20mb.mp4',
                    '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_20mb.mp4',
                    '4K' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_3840x2160_20mb.mp4',
                ],
                'tmdb_id' => 19995,
                'trailer_url' => 'https://www.youtube.com/watch?v=5PSNL1qE6VY',
                'is_premium' => true,
                'genres' => ['Action', 'Adventure', 'Fantasy', 'Science Fiction']
            ],
            [
                'title' => 'The Avengers',
                'description' => 'Earth\'s mightiest heroes must come together and learn to fight as a team if they are going to stop the mischievous Loki and his alien army from enslaving humanity.',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/RYMX2wcKCBAr24UyPD7xwmjaTn.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/9BBTo63ANSmhC4e6r62OJFuK2GL.jpg',
                'release_year' => 2012,
                'rating' => 8.0,
                'duration' => 143,
                'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_30mb.mp4',
                'video_qualities' => [
                    '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_30mb.mp4',
                    '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_30mb.mp4',
                    '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_30mb.mp4',
                    '4K' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_3840x2160_30mb.mp4',
                ],
                'tmdb_id' => 24428,
                'trailer_url' => 'https://www.youtube.com/watch?v=eOrNdBpGMv8',
                'is_premium' => true,
                'genres' => ['Action', 'Adventure', 'Science Fiction']
            ],
        ];

        foreach ($movies as $movieData) {
            $genres = $movieData['genres'];
            unset($movieData['genres']);

            $movie = Movie::firstOrCreate(
                ['title' => $movieData['title']],
                $movieData
            );

            // Attach genres
            $genreIds = Genre::whereIn('name', $genres)->pluck('id');
            $movie->genres()->sync($genreIds);
        }
    }
}