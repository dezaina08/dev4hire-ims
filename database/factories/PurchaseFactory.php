<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => rand(1, 25),
            'purchase_date' => fake()->dateTimeBetween('-1 month'),
            'is_approved' => rand(0, 1),
            'created_by' => rand(1, 5),
            'updated_by' => rand(1, 5),
        ];
    }
}
