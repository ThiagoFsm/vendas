<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamanhosSeeder extends Seeder
{
    public function run(): void
    {
        $tamanhos = [
            ['descricao' => '150g', 'ativo' => true],
            ['descricao' => '350g', 'ativo' => true],
            ['descricao' => '500g', 'ativo' => true],
            ['descricao' => '200g - coração', 'ativo' => true],
        ];

        foreach ($tamanhos as $tamanho) {
            DB::table('tamanhos')->updateOrInsert(
                ['descricao' => $tamanho['descricao']],
                [
                    'ativo' => $tamanho['ativo'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
