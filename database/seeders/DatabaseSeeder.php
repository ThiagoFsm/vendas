<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BairrosSeeder::class,
            TipoProdutosSeeder::class,
            SaboresSeeder::class,
            TamanhosSeeder::class,
            VendedoresSeeder::class,
            ProdutosSeeder::class,
        ]);
    }
}
