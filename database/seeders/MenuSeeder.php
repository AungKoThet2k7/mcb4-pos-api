<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            // ကြက်သားဟော့ပ် (chicken-hotpot) → id 1
            [
                'title' => 'Classic Chicken Mala Xiang Guo',
                'slug' => 'classic-chicken-mala-xiang-guo',
                'price' => 12000,
                'image' => 'menus/classic-chicken.jpg',
                'category_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Extra Spicy Chicken Mala Xiang Guo',
                'slug' => 'extra-spicy-chicken-mala-xiang-guo',
                'price' => 13000,
                'image' => 'menus/extra-spicy-chicken.jpg',
                'category_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ဝက်သားဟော့ပ် (pork-hotpot) → id 2
            [
                'title' => 'Pork Belly Mala Xiang Guo',
                'slug' => 'pork-belly-mala-xiang-guo',
                'price' => 14000,
                'image' => 'menus/pork-belly.jpg',
                'category_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Classic Pork Mala Xiang Guo',
                'slug' => 'classic-pork-mala-xiang-guo',
                'price' => 13500,
                'image' => 'menus/classic-pork.jpg',
                'category_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // လိပ်မင်းဟော့ပ် (beef-hotpot) → id 3
            [
                'title' => 'Classic Beef Mala Xiang Guo',
                'slug' => 'classic-beef-mala-xiang-guo',
                'price' => 14500,
                'image' => 'menus/classic-beef.jpg',
                'category_id' => 3,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Spicy Beef Mala Xiang Guo',
                'slug' => 'spicy-beef-mala-xiang-guo',
                'price' => 15000,
                'image' => 'menus/spicy-beef.jpg',
                'category_id' => 3,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ငါးဟော့ပ် (fish-hotpot) → id 4
            [
                'title' => 'Fish Fillet Mala Xiang Guo',
                'slug' => 'fish-fillet-mala-xiang-guo',
                'price' => 15500,
                'image' => 'menus/fish-fillet.jpg',
                'category_id' => 4,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Duck Mala Xiang Guo',
                'slug' => 'duck-mala-xiang-guo',
                'price' => 16500,
                'image' => 'menus/duck.jpg',
                'category_id' => 4,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ပင်လယ်စာဟော့ပ် (seafood-hotpot) → id 5
            [
                'title' => 'Seafood Combo Mala Xiang Guo',
                'slug' => 'seafood-combo-mala-xiang-guo',
                'price' => 18000,
                'image' => 'menus/seafood-combo.jpg',
                'category_id' => 5,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Shrimp Mala Xiang Guo',
                'slug' => 'shrimp-mala-xiang-guo',
                'price' => 17000,
                'image' => 'menus/shrimp.jpg',
                'category_id' => 5,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Crab Mala Xiang Guo',
                'slug' => 'crab-mala-xiang-guo',
                'price' => 22000,
                'image' => 'menus/crab.jpg',
                'category_id' => 5,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ဟင်းသီးဟော့ပ် (vegetable-hotpot) → id 6
            [
                'title' => 'Vegetable Mix Mala Xiang Guo',
                'slug' => 'vegetable-mix-mala-xiang-guo',
                'price' => 10000,
                'image' => 'menus/vegetable-mix.jpg',
                'category_id' => 6,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Tofu Mala Xiang Guo',
                'slug' => 'tofu-mala-xiang-guo',
                'price' => 9500,
                'image' => 'menus/tofu.jpg',
                'category_id' => 6,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // အာလူးဟော့ပ် (potato-hotpot) → id 7
            [
                'title' => 'Mushroom Lover Mala Xiang Guo',
                'slug' => 'mushroom-lover-mala-xiang-guo',
                'price' => 10500,
                'image' => 'menus/mushroom.jpg',
                'category_id' => 7,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // တောစာဟော့ပ် (wildmeat-hotpot) → id 8
            [
                'title' => 'Chicken & Beef Mala Xiang Guo',
                'slug' => 'chicken-beef-mala-xiang-guo',
                'price' => 16000,
                'image' => 'menus/chicken-beef.jpg',
                'category_id' => 8,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ပေါင်းစပ်ဟော့ပ် (mixed-hotpot) → id 9
            [
                'title' => 'Signature Mala Xiang Guo',
                'slug' => 'signature-mala-xiang-guo',
                'price' => 17000,
                'image' => 'menus/signature.jpg',
                'category_id' => 9,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ဟင်းရည်အာဟာရ (nutritious-broth) → id 10
            [
                'title' => 'Premium Wagyu Mala Xiang Guo',
                'slug' => 'premium-wagyu-mala-xiang-guo',
                'price' => 25000,
                'image' => 'menus/premium-wagyu.jpg',
                'category_id' => 10,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Premium Lobster Mala Xiang Guo',
                'slug' => 'premium-lobster-mala-xiang-guo',
                'price' => 30000,
                'image' => 'menus/premium-lobster.jpg',
                'category_id' => 10,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
