<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets and electronic devices including smartphones, laptops, tablets, and accessories.',
                'image' => 'https://placehold.co/400x400/6366f1/ffffff?text=Electronics',
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Trendy fashion items for men, women, and kids. From casual wear to formal attire.',
                'image' => 'https://placehold.co/400x400/ec4899/ffffff?text=Clothing',
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'A vast collection of books across all genres - fiction, non-fiction, educational, and more.',
                'image' => 'https://placehold.co/400x400/8b5cf6/ffffff?text=Books',
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Everything you need for your home and garden - furniture, decor, tools, and plants.',
                'image' => 'https://placehold.co/400x400/22c55e/ffffff?text=Home+Garden',
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Gear up for adventure with our sports equipment, outdoor gear, and fitness accessories.',
                'image' => 'https://placehold.co/400x400/f97316/ffffff?text=Sports',
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'description' => 'Premium beauty products, skincare, healthcare items, and wellness essentials.',
                'image' => 'https://placehold.co/400x400/ef4444/ffffff?text=Beauty',
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Fun for all ages! Board games, video games, toys, puzzles, and more.',
                'image' => 'https://placehold.co/400x400/eab308/ffffff?text=Toys+Games',
            ],
            [
                'name' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'description' => 'Gourmet food items, snacks, beverages, and specialty ingredients.',
                'image' => 'https://placehold.co/400x400/14b8a6/ffffff?text=Food',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
