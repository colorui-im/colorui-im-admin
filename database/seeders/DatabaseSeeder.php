<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Store;
use App\Models\Sku;
use App\Models\Classify;
use App\Models\Product;
use App\Models\Method;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory(10)->create();

    }
}
