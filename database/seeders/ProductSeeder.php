<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            [
                'category' => 'electronics',
                'products' => [
                    ['name' => 'Wireless Bluetooth Headphones', 'price' => 79.99, 'stock' => 45, 'description' => 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and crystal-clear sound quality.'],
                    ['name' => 'Smart Watch Pro', 'price' => 199.99, 'stock' => 30, 'description' => 'Advanced smartwatch with health monitoring, GPS, water resistance, and 7-day battery life.'],
                    ['name' => 'Portable Power Bank 20000mAh', 'price' => 49.99, 'stock' => 60, 'description' => 'High-capacity portable charger with fast charging support for all your devices.'],
                    ['name' => 'USB-C Hub Adapter', 'price' => 39.99, 'stock' => 80, 'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader, and power delivery.'],
                    ['name' => 'Wireless Charging Pad', 'price' => 29.99, 'stock' => 5, 'description' => 'Fast wireless charging pad compatible with all Qi-enabled devices.'],
                ],
            ],
            // Clothing
            [
                'category' => 'clothing',
                'products' => [
                    ['name' => 'Classic Cotton T-Shirt', 'price' => 24.99, 'stock' => 100, 'description' => 'Comfortable 100% cotton t-shirt available in multiple colors. Perfect for everyday wear.'],
                    ['name' => 'Slim Fit Jeans', 'price' => 59.99, 'stock' => 75, 'description' => 'Modern slim fit jeans with stretch comfort. Durable denim that looks great.'],
                    ['name' => 'Hooded Sweatshirt', 'price' => 44.99, 'stock' => 50, 'description' => 'Cozy fleece-lined hoodie perfect for casual outings or lounging at home.'],
                    ['name' => 'Running Sneakers', 'price' => 89.99, 'stock' => 35, 'description' => 'Lightweight running shoes with cushioned sole and breathable mesh upper.'],
                    ['name' => 'Winter Jacket', 'price' => 129.99, 'stock' => 8, 'description' => 'Warm insulated jacket with waterproof exterior. Perfect for cold weather.'],
                ],
            ],
            // Books
            [
                'category' => 'books',
                'products' => [
                    ['name' => 'The Art of Programming', 'price' => 34.99, 'stock' => 40, 'description' => 'A comprehensive guide to software development best practices and design patterns.'],
                    ['name' => 'Mystery at Midnight', 'price' => 14.99, 'stock' => 55, 'description' => 'A thrilling mystery novel that will keep you on the edge of your seat.'],
                    ['name' => 'Cooking Made Simple', 'price' => 29.99, 'stock' => 30, 'description' => 'Easy-to-follow recipes for beginners with step-by-step instructions.'],
                    ['name' => 'Business Strategy 101', 'price' => 24.99, 'stock' => 25, 'description' => 'Learn the fundamentals of business strategy from industry experts.'],
                ],
            ],
            // Home & Garden
            [
                'category' => 'home-garden',
                'products' => [
                    ['name' => 'Ceramic Plant Pot Set', 'price' => 34.99, 'stock' => 40, 'description' => 'Set of 3 beautiful ceramic pots in different sizes. Perfect for indoor plants.'],
                    ['name' => 'LED Desk Lamp', 'price' => 44.99, 'stock' => 55, 'description' => 'Adjustable LED lamp with multiple brightness levels and USB charging port.'],
                    ['name' => 'Memory Foam Pillow', 'price' => 39.99, 'stock' => 70, 'description' => 'Ergonomic memory foam pillow for a comfortable nights sleep.'],
                    ['name' => 'Garden Tool Set', 'price' => 54.99, 'stock' => 3, 'description' => 'Complete 12-piece garden tool set with carrying bag. Everything you need for gardening.'],
                ],
            ],
            // Sports & Outdoors
            [
                'category' => 'sports-outdoors',
                'products' => [
                    ['name' => 'Yoga Mat Premium', 'price' => 29.99, 'stock' => 65, 'description' => 'Non-slip yoga mat with alignment lines. Extra thick for comfort.'],
                    ['name' => 'Resistance Bands Set', 'price' => 19.99, 'stock' => 90, 'description' => 'Set of 5 resistance bands with different strength levels for versatile workouts.'],
                    ['name' => 'Camping Tent 4-Person', 'price' => 149.99, 'stock' => 15, 'description' => 'Waterproof camping tent with easy setup. Fits 4 people comfortably.'],
                    ['name' => 'Stainless Steel Water Bottle', 'price' => 24.99, 'stock' => 80, 'description' => 'Double-wall insulated bottle keeps drinks cold for 24 hours or hot for 12 hours.'],
                ],
            ],
            // Beauty & Health
            [
                'category' => 'beauty-health',
                'products' => [
                    ['name' => 'Vitamin C Serum', 'price' => 34.99, 'stock' => 45, 'description' => 'Brightening serum with 20% Vitamin C for radiant, youthful skin.'],
                    ['name' => 'Electric Toothbrush', 'price' => 59.99, 'stock' => 40, 'description' => 'Sonic toothbrush with 5 cleaning modes and 2-minute timer.'],
                    ['name' => 'Aromatherapy Diffuser', 'price' => 39.99, 'stock' => 35, 'description' => 'Ultrasonic essential oil diffuser with LED lights and auto shut-off.'],
                    ['name' => 'Multivitamin Gummies', 'price' => 19.99, 'stock' => 0, 'description' => 'Delicious daily multivitamins for adults. 60 gummies per bottle.'],
                ],
            ],
            // Toys & Games
            [
                'category' => 'toys-games',
                'products' => [
                    ['name' => 'Strategy Board Game', 'price' => 44.99, 'stock' => 25, 'description' => 'Award-winning strategy game for 2-4 players. Hours of entertainment!'],
                    ['name' => 'Building Blocks 500pc', 'price' => 29.99, 'stock' => 50, 'description' => 'Creative building blocks set with 500 pieces. Compatible with major brands.'],
                    ['name' => 'Remote Control Car', 'price' => 49.99, 'stock' => 30, 'description' => 'High-speed RC car with rechargeable battery. Great for indoor and outdoor use.'],
                ],
            ],
            // Food & Beverages
            [
                'category' => 'food-beverages',
                'products' => [
                    ['name' => 'Organic Coffee Beans', 'price' => 18.99, 'stock' => 60, 'description' => 'Premium organic Arabica coffee beans. Medium roast with rich flavor.'],
                    ['name' => 'Gourmet Chocolate Box', 'price' => 29.99, 'stock' => 40, 'description' => 'Assorted premium chocolates. Perfect for gifting or self-indulgence.'],
                    ['name' => 'Green Tea Collection', 'price' => 14.99, 'stock' => 55, 'description' => 'Selection of 6 different green teas from around the world.'],
                ],
            ],
        ];

        foreach ($products as $categoryProducts) {
            $category = Category::where('slug', $categoryProducts['category'])->first();

            if ($category) {
                foreach ($categoryProducts['products'] as $productData) {
                    Product::create([
                        'category_id' => $category->id,
                        'name' => $productData['name'],
                        'slug' => \Illuminate\Support\Str::slug($productData['name']),
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'stock_quantity' => $productData['stock'],
                        'low_stock_threshold' => 10,
                        'image' => 'https://placehold.co/400x400/e2e8f0/475569?text=' . urlencode($productData['name']),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
