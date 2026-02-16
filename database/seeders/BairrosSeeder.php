<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bairro;

class BairrosSeeder extends Seeder
{
    public function run(): void
    {
        $bairros = [
            'Aero Rancho', 'Amambaí', 'Bandeirantes', 'Batistão', 'Bela Vista',
            'Cabreúva', 'Caiobá', 'Carandá Bosque', 'Carlota', 'Carvalho',
            'Centenário', 'Centro', 'Chácara Cachoeira', 'Coophavila II',
            'Coronel Antonino', 'Cruzeiro', 'Estrela de Dalva', 'Estrela do Sul',
            'Glória', 'Guanandi', 'Itanhangá', 'Jardim dos Estados', 'Jardim Paulista',
            'Jockey Club', 'Lageado', 'Leblon', 'Los Angeles', 'Mata do Jacinto',
            'Monte Castelo', 'Monte Líbano', 'Moreninha', 'Nasser', 'Noroeste',
            'Nova Bahia', 'Nova Campo Grande', 'Panamá', 'Parati', 'Piratininga',
            'Planalto', 'Popular', 'Rita Vieira', 'Santa Fé', 'Santo Amaro',
            'Santo Antônio', 'São Bento', 'São Conrado', 'São Francisco',
            'Seminário', 'Taquarussu', 'Taveirópolis', 'Tijuca', 'Tiradentes',
            'União', 'Universitário', 'Veraneio', 'Vila Jacy', 'Vila Sobrinho',
            'Vilas Boas', 'Zé Pereira', 'Lar do Trabalhador'
        ];

        foreach ($bairros as $nome) {
            // Verifica se já existe um bairro com essa descrição antes de inserir
            Bairro::firstOrCreate(
                ['descricao' => $nome]
            );
        }
    }
}
