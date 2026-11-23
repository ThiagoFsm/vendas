<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutosSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            // Sobremesas (tipo_id: 1) - Tamanhos 5, 6, 7 e 8 (Travessa P, Travessa G, Taça P, Taça G)
            // 8: Pavê de Sonho de Valsa
            ['tipo_id' => 1, 'sabor_id' => 8, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 8, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 8, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 8, 'tamanho_id' => 8, 'valor' => 115.00],

            // 9: Torta de Ferrero Rocher
            ['tipo_id' => 1, 'sabor_id' => 9, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 9, 'tamanho_id' => 6, 'valor' => 120.00],
            ['tipo_id' => 1, 'sabor_id' => 9, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 9, 'tamanho_id' => 8, 'valor' => 135.00],

            // 10: Travessa de Ouro Branco
            ['tipo_id' => 1, 'sabor_id' => 10, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 10, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 10, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 10, 'tamanho_id' => 8, 'valor' => 115.00],

            // 11: Pavê de Nozes com Doce de Leite
            ['tipo_id' => 1, 'sabor_id' => 11, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 11, 'tamanho_id' => 6, 'valor' => 120.00],
            ['tipo_id' => 1, 'sabor_id' => 11, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 11, 'tamanho_id' => 8, 'valor' => 135.00],

            // 12: Baba de Moça de Morango
            ['tipo_id' => 1, 'sabor_id' => 12, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 12, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 12, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 12, 'tamanho_id' => 8, 'valor' => 115.00],

            // 13: Baba de Moça de Abacaxi
            ['tipo_id' => 1, 'sabor_id' => 13, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 13, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 13, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 13, 'tamanho_id' => 8, 'valor' => 115.00],

            // 14: Bombom Aberto de Morango
            ['tipo_id' => 1, 'sabor_id' => 14, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 14, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 14, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 14, 'tamanho_id' => 8, 'valor' => 115.00],

            // 15: Bombom Aberto de Uva
            ['tipo_id' => 1, 'sabor_id' => 15, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 15, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 15, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 15, 'tamanho_id' => 8, 'valor' => 115.00],

            // 16: Travessa de Maracujá com Brigadeiro
            ['tipo_id' => 1, 'sabor_id' => 16, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 16, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 16, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 16, 'tamanho_id' => 8, 'valor' => 115.00],

            // 17: Travessa de Maracujá com Morango
            ['tipo_id' => 1, 'sabor_id' => 17, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 17, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 17, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 17, 'tamanho_id' => 8, 'valor' => 115.00],

            // 18: Torta de Kinder Bueno
            ['tipo_id' => 1, 'sabor_id' => 18, 'tamanho_id' => 5, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 18, 'tamanho_id' => 6, 'valor' => 100.00],
            ['tipo_id' => 1, 'sabor_id' => 18, 'tamanho_id' => 7, 'valor' => 70.00],
            ['tipo_id' => 1, 'sabor_id' => 18, 'tamanho_id' => 8, 'valor' => 115.00],

            // 19: Banoffee
            ['tipo_id' => 1, 'sabor_id' => 19, 'tamanho_id' => 5, 'valor' => 50.00],
            ['tipo_id' => 1, 'sabor_id' => 19, 'tamanho_id' => 6, 'valor' => 90.00],
            ['tipo_id' => 1, 'sabor_id' => 19, 'tamanho_id' => 7, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 19, 'tamanho_id' => 8, 'valor' => 105.00],

            // 20: Torta de limão
            ['tipo_id' => 1, 'sabor_id' => 20, 'tamanho_id' => 5, 'valor' => 50.00],
            ['tipo_id' => 1, 'sabor_id' => 20, 'tamanho_id' => 6, 'valor' => 90.00],
            ['tipo_id' => 1, 'sabor_id' => 20, 'tamanho_id' => 7, 'valor' => 60.00],
            ['tipo_id' => 1, 'sabor_id' => 20, 'tamanho_id' => 8, 'valor' => 105.00],

            // 21: Pudim
            ['tipo_id' => 1, 'sabor_id' => 21, 'tamanho_id' => 5, 'valor' => 50.00],
            ['tipo_id' => 1, 'sabor_id' => 21, 'tamanho_id' => 6, 'valor' => 70.00],

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
