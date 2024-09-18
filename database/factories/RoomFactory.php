<?php

namespace Database\Factories;

use App\Models\Room;
use App\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'type' => $this->faker->randomElement(RoomType::class),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
