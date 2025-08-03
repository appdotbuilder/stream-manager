<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advertisements = [
            [
                'name' => 'Homepage Hero Banner',
                'code' => '<div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 rounded-lg text-white text-center">
                    <h3 class="font-bold">🎬 Premium Streaming Experience</h3>
                    <p class="text-sm">Upgrade to Premium for 4K quality and ad-free viewing!</p>
                </div>',
                'placement' => 'homepage_hero',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Homepage Sidebar Ad',
                'code' => '<div class="bg-gray-800 p-4 rounded-lg text-white text-center">
                    <h4 class="font-semibold mb-2">📱 Download Our App</h4>
                    <p class="text-sm text-gray-300 mb-3">Watch anywhere, anytime</p>
                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-sm">Download Now</button>
                </div>',
                'placement' => 'homepage_sidebar',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Movie Detail Top Ad',
                'code' => '<div class="bg-yellow-600 text-black p-3 rounded-lg text-center">
                    <span class="font-semibold">🍿 Get snacks delivered while you watch!</span>
                </div>',
                'placement' => 'movie_detail_top',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Movie Detail Bottom Ad',
                'code' => '<div class="bg-gradient-to-r from-green-600 to-blue-600 p-6 rounded-lg text-white text-center">
                    <h3 class="text-xl font-bold mb-2">💎 Upgrade to Premium</h3>
                    <p class="mb-4">Enjoy unlimited streaming, 4K quality, and exclusive content</p>
                    <button class="bg-white text-green-600 font-semibold px-6 py-2 rounded-lg">Start Free Trial</button>
                </div>',
                'placement' => 'movie_detail_bottom',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Player Overlay Ad',
                'code' => '<div class="bg-black bg-opacity-75 text-white p-2 rounded text-sm">
                    <span>⏸️ Pause ads with Premium</span>
                </div>',
                'placement' => 'player_overlay',
                'is_active' => true,
                'sort_order' => 1,
            ],
        ];

        foreach ($advertisements as $ad) {
            Advertisement::firstOrCreate(
                [
                    'name' => $ad['name'],
                    'placement' => $ad['placement']
                ],
                $ad
            );
        }
    }
}