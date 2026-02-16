<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendedoresSeeder extends Seeder
{
    public function run(): void
    {
        $vendedores = [
            'Thiago',
            'Jaqueline',
            'Giovana',
            'Edson'
        ];

        foreach ($vendedores as $nome) {
            DB::table('vendedores')->updateOrInsert(
                ['nome' => $nome],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
