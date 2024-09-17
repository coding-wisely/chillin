<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {

        return [
            'amount' => $this->faker->numberBetween(100, 1200),
            'spent_at' => $this->faker->dateTimeBetween('-3 months'),
            'created_at' => $this->faker->dateTimeBetween('-3 months'),
            'updated_at' => Carbon::now(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(), // Use existing or create new if none
            'user_id' => User::factory(),
        ];
    }

    public function forUser(User $user): self
    {
        return $this->state(function () use ($user) {
            return ['user_id' => $user->id];
        });
    }
}
