<?php

namespace Database\Seeders;

use App\Models\Store;
use Database\Factories\StoreFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = ['DIS', 'BASTA', 'RODA', 'IDEA', 'PIJACA', 'LIDL'];

        foreach ($stores as $store){

            Store::factory()->create([
                'name' => $store
            ]);
        }
    }
}
