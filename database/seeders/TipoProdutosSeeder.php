<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoProdutosSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['descricao' => 'Sobremesa', 'ativo' => false],
            ['descricao' => 'Ovo de Páscoa', 'ativo' => true],
            ['descricao' => 'Coração Lapidado', 'ativo' => true],
        ];

        foreach ($tipos as $tipo) {
            // Verifica pela descrição para não duplicar, mas atualiza o status 'ativo'
            DB::table('tipo_produtos')->updateOrInsert(
                ['descricao' => $tipo['descricao']],
                [
                    'ativo' => $tipo['ativo'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
