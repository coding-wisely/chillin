<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
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
            'email' => 'milan@chillinpattaya.com',
        ]);
        $vladimir = User::factory()->create([
            'name' => 'Vladimir Nikolić',
            'email' => 'vladimir@chillinpattaya.com',
        ]);
        $sexy = User::factory()->create([
            'name' => 'Sexy girl',
            'email' => 'sexy@chillinpattaya.com',
        ]);
        Category::factory()->create([
            'title' => 'Food & Drinks',

        ]);
        Category::factory()->create([
            'title' => 'Bills',

        ]);
        Category::factory()->create([
            'title' => 'Transport',

        ]);
        Category::factory()->create([
            'title' => 'Accommodation',

        ]);
        Category::factory()->create([
            'title' => 'Entertainment',

        ]);
        Category::factory()->create([
            'title' => 'Other',
        ]);

        Room::factory()->create([
            'title' => 'Room 101',
            'description' => 'Room 1 description',
            'price' => 1000,
            'type' => RoomType::standard,
        ]);
        Room::factory()->create([
            'title' => 'Room 201',
            'description' => 'Room 2 description',
            'price' => 2000,
            'type' => RoomType::medium,
        ]);
        Room::factory()->create([
            'title' => 'Room 301',
            'description' => 'Room 3 description',
            'price' => 2000,
            'type' => RoomType::large,
        ]);
        Room::factory()->create([
            'title' => 'Room 102',
            'description' => 'Room 4 description',
            'price' => 2000,
            'type' => RoomType::standard,
        ]);
        Room::factory()->create([
            'title' => 'Room 202',
            'description' => 'Room 5 description',
            'price' => 2000,
            'type' => RoomType::medium,
        ]);
        Room::factory()->create([
            'title' => 'Room 302',
            'description' => 'Room 6 description',
            'price' => 2000,
            'type' => RoomType::large,
        ]);
        Room::factory()->create([
            'title' => 'Room 103',
            'description' => 'Room 7 description',
            'price' => 2000,
            'type' => RoomType::standard,
        ]);
        Room::factory()->create([
            'title' => 'Room 203',
            'description' => 'Room 8 description',
            'price' => 2000,
            'type' => RoomType::medium,
        ]);
        Room::factory()->create([
            'title' => 'Room 303',
            'description' => 'Room 9 description',
            'price' => 2000,
            'type' => RoomType::large,
        ]);

    }
}
