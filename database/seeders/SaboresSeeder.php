<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaboresSeeder extends Seeder
{
    public function run(): void
    {
        $sabores = [
            ['descricao' => 'Palha - Leite Ninho com Nutella', 'ativo' => true],
            ['descricao' => 'Palha - Leite Ninho com Oreo', 'ativo' => true],
            ['descricao' => 'Palha - Brigadeiro', 'ativo' => true],
            ['descricao' => 'Palha - Paçoca', 'ativo' => true],
            ['descricao' => 'Tradicional - Beijinho', 'ativo' => true],
            ['descricao' => 'Tradicional - Brigadeiro', 'ativo' => true],
            ['descricao' => 'Tradicional - Duo (Beijinho + Brigadeiro)', 'ativo' => true],
        ];

        foreach ($sabores as $sabor) {
            DB::table('sabores')->updateOrInsert(
                ['descricao' => $sabor['descricao']],
                [
                    'ativo' => $sabor['ativo'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
