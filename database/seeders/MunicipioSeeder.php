<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Departamento;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        $municipios = [
            'Antioquia' => ['Medellín', 'Envigado'],
            'Cundinamarca' => ['Bogotá', 'Facatativá'],
            'Nariño' => ['Pasto', 'Ipiales'],
            'Tolima' => ['Ibagué', 'Espinal'],
            'Valle del Cauca' => ['Cali', 'Palmira'],
        ];

        foreach ($municipios as $deptoNombre => $nombres) {
            $departamento = Departamento::where('nombre', $deptoNombre)->first();
            if ($departamento) {
                foreach ($nombres as $nombre) {
                    Municipio::create([
                        'nombre' => $nombre,
                        'departamento_id' => $departamento->id,
                    ]);
                }
            }
        }
    }
}
