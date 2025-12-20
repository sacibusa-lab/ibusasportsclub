<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\StoryItem::query()->delete();
        \App\Models\Story::query()->delete();

        $matchDay = \App\Models\Story::create([
            'title' => 'Match Day 01',
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        \App\Models\StoryItem::create([
            'story_id' => $matchDay->id,
            'media_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=1000',
            'type' => 'image',
            'order' => 0,
        ]);
        \App\Models\StoryItem::create([
            'story_id' => $matchDay->id,
            'media_url' => 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?auto=format&fit=crop&q=80&w=1000',
            'type' => 'image',
            'order' => 1,
        ]);

        // Update group thumbnail
        $matchDay->update(['thumbnail_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=200']);

        $training = \App\Models\Story::create([
            'title' => 'Training Camp',
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        \App\Models\StoryItem::create([
            'story_id' => $training->id,
            'media_url' => 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?auto=format&fit=crop&q=80&w=1000',
            'type' => 'image',
            'order' => 0,
        ]);

        $training->update(['thumbnail_url' => 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?auto=format&fit=crop&q=80&w=200']);
    }
}
