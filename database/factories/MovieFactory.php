<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'poster_url' => 'https://picsum.photos/300/450?random=' . fake()->numberBetween(1, 1000),
            'backdrop_url' => 'https://picsum.photos/1280/720?random=' . fake()->numberBetween(1, 1000),
            'release_year' => fake()->numberBetween(1990, 2024),
            'rating' => fake()->randomFloat(1, 1, 10),
            'duration' => fake()->numberBetween(80, 180),
            'video_url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
            'video_qualities' => [
                '480p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_640x360_1mb.mp4',
                '720p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                '1080p' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1920x1080_1mb.mp4',
            ],
            'tmdb_id' => fake()->unique()->numberBetween(1000, 99999),
            'imdb_id' => 'tt' . fake()->unique()->numberBetween(1000000, 9999999),
            'trailer_url' => 'https://www.youtube.com/watch?v=' . fake()->regexify('[A-Za-z0-9_-]{11}'),
            'is_premium' => fake()->boolean(30), // 30% premium
            'is_active' => true,
        ];
    }
}