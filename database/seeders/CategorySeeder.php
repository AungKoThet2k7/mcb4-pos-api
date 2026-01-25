<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=[
            [
        'title' => 'ကြက်သားဟော့ပ်',
        'slug' => 'chicken-hotpot',
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'ဝက်သားဟော့ပ်',
        'slug' => 'pork-hotpot',
        'user_id' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'လိပ်မင်းဟော့ပ်',
        'slug' => 'beef-hotpot',
        'user_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'ငါးဟော့ပ်',
        'slug' => 'fish-hotpot',
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'ပင်လယ်စာဟော့ပ်',
        'slug' => 'seafood-hotpot',
        'user_id' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'ဟင်းသီးဟော့ပ်',
        'slug' => 'vegetable-hotpot',
        'user_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'အာလူးဟော့ပ်',
        'slug' => 'potato-hotpot',
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'တောစာဟော့ပ်',
        'slug' => 'wildmeat-hotpot',
        'user_id' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'ပေါင်းစပ်ဟော့ပ်',
        'slug' => 'mixed-hotpot',
        'user_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'title' => 'ဟင်းရည်အာဟာရ',
        'slug' => 'nutritious-broth',
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],

        ];
    Category::insert($categories);
    }
}
