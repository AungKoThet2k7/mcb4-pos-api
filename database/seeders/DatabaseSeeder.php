<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->cleanStorage();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
        ]);
    }

    private function cleanStorage(): void
    {
        // Skip Cleaning Storage if environment is Not Local
        if (! app()->environment('local')) {
            return;
        }

        // Delete All Files in Root Storage
        $photos = Storage::allFiles('/');

        Storage::delete($photos);

        $this->command->info('Storage cleaned.');
    }
}
