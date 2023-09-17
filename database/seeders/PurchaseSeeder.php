<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Seeder;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchaseSeeder extends Seeder
{
    private $total = 0;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 50) as $index) {
            Purchase::factory(1)
            ->create([
                'purchase_number' => IdGenerator::generate([
                    'table' => 'purchases',
                    'field' => 'purchase_number',
                    'length' => 9,
                    'prefix' => 'PN-'
                ]),
                'total' => 0,
            ])
            ->each(function($purchase) {
                PurchaseItem::factory(2)->create([
                    'purchase_id' => $purchase->id,
                ])->each(function($PurchaseItem) {
                    $this->total += $PurchaseItem->total;
                });
                $purchase->total = $this->total;
                $purchase->save();
                $this->total = 0;
            });
        }
    }
}
