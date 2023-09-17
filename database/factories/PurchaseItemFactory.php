<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseItem>
 */
class PurchaseItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = rand(5, 100);
        $unitCost = rand(1, 20) * 10;
        return [
            'purchase_id' => rand(1, 200),
            'product_id' => rand(1, 1000),
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'total' => $quantity * $unitCost,
        ];
    }
}
