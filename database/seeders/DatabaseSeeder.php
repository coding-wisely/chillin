<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\RoomType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $milan = User::factory()->create([
            'name' => 'Milan Štrbac',
            'email' => 'milanstrbac76@gmail.com',
        ]);
        $vladimir = User::factory()->create([
            'name' => 'Vladimir Nikolić',
            'email' => 'vladimir@codingwisely.com',
        ]);
    }
}
