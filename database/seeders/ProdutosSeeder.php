<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutosSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            // Ovos de Páscoa (tipo_id: 2) - Tamanhos 1, 2 e 3 (150g, 350g, 500g)
            ['tipo_id' => 2, 'sabor_id' => 1, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 1, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 1, 'tamanho_id' => 3, 'valor' => 80.00],
            ['tipo_id' => 2, 'sabor_id' => 2, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 2, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 2, 'tamanho_id' => 3, 'valor' => 80.00],
            ['tipo_id' => 2, 'sabor_id' => 3, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 3, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 3, 'tamanho_id' => 3, 'valor' => 80.00],
            ['tipo_id' => 2, 'sabor_id' => 4, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 4, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 4, 'tamanho_id' => 3, 'valor' => 80.00],
            ['tipo_id' => 2, 'sabor_id' => 5, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 5, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 5, 'tamanho_id' => 3, 'valor' => 80.00],
            ['tipo_id' => 2, 'sabor_id' => 6, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 6, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 6, 'tamanho_id' => 3, 'valor' => 80.00],
            ['tipo_id' => 2, 'sabor_id' => 7, 'tamanho_id' => 1, 'valor' => 30.00],
            ['tipo_id' => 2, 'sabor_id' => 7, 'tamanho_id' => 2, 'valor' => 60.00],
            ['tipo_id' => 2, 'sabor_id' => 7, 'tamanho_id' => 3, 'valor' => 80.00],

            // Coração Lapidado (tipo_id: 3) - Tamanho 4 (200g - coração)
            ['tipo_id' => 3, 'sabor_id' => 1, 'tamanho_id' => 4, 'valor' => 35.00],
            ['tipo_id' => 3, 'sabor_id' => 2, 'tamanho_id' => 4, 'valor' => 35.00],
            ['tipo_id' => 3, 'sabor_id' => 3, 'tamanho_id' => 4, 'valor' => 35.00],
            ['tipo_id' => 3, 'sabor_id' => 4, 'tamanho_id' => 4, 'valor' => 35.00],
            ['tipo_id' => 3, 'sabor_id' => 5, 'tamanho_id' => 4, 'valor' => 35.00],
            ['tipo_id' => 3, 'sabor_id' => 6, 'tamanho_id' => 4, 'valor' => 35.00],
            ['tipo_id' => 3, 'sabor_id' => 7, 'tamanho_id' => 4, 'valor' => 35.00],
        ];

        foreach ($produtos as $p) {
            DB::table('produtos')->updateOrInsert(
                [
                    'tipo_produto_id' => $p['tipo_id'],
                    'sabor_id'        => $p['sabor_id'],
                    'tamanho_id'      => $p['tamanho_id']
                ],
                [
                    'valor_produto'   => $p['valor'],
                    'ativo'           => true,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ]
            );
        }
    }
}
