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
            ['descricao' => 'Pavê de Sonho de Valsa', 'ativo' => true],
            ['descricao' => 'Torta de Ferrero Rocher', 'ativo' => true],
            ['descricao' => 'Travessa de Ouro Branco', 'ativo' => true],
            ['descricao' => 'Pavê de Nozes com Doce de Leite', 'ativo' => true],
            ['descricao' => 'Baba de Moça com Morango', 'ativo' => true],
            ['descricao' => 'Baba de Moça com Abacaxi', 'ativo' => true],
            ['descricao' => 'Bombom Aberto de Morango', 'ativo' => true],
            ['descricao' => 'Bombom Aberto de Uva', 'ativo' => true],
            ['descricao' => 'Travessa de Maracujá com Brigadeiro', 'ativo' => true],
            ['descricao' => 'Travessa de Maracujá com Morango', 'ativo' => true],
            ['descricao' => 'Torta de Kinder Bueno', 'ativo' => true],
            ['descricao' => 'Banoffee', 'ativo' => true],
            ['descricao' => 'Torta de Limão', 'ativo' => true],
            ['descricao' => 'Pudim', 'ativo' => true],
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
