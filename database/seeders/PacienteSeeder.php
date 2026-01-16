<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Genero;
use App\Models\Municipio;
use App\Models\Paciente;
use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir pacientes de prueba
        $pacientesData = [
            [
                'tipo_documento' => 'CC',
                'numero_documento' => '1023456789',
                'primer_nombre' => 'Carlos',
                'segundo_nombre' => 'Andrés',
                'primer_apellido' => 'Gómez',
                'segundo_apellido' => 'López',
                'genero' => 'Masculino',
                'departamento' => 'Antioquia',
                'municipio' => 'Medellín',
                'correo' => 'carlos.gomez@example.com',
            ],
            [
                'tipo_documento' => 'CC',
                'numero_documento' => '1034567890',
                'primer_nombre' => 'María',
                'segundo_nombre' => 'Fernanda',
                'primer_apellido' => 'Rodríguez',
                'segundo_apellido' => 'Sánchez',
                'genero' => 'Femenino',
                'departamento' => 'Cundinamarca',
                'municipio' => 'Bogotá',
                'correo' => 'maria.rodriguez@example.com',
            ],
            [
                'tipo_documento' => 'TI',
                'numero_documento' => '1122334455',
                'primer_nombre' => 'Lucas',
                'segundo_nombre' => '',
                'primer_apellido' => 'Martínez',
                'segundo_apellido' => 'Díaz',
                'genero' => 'Masculino',
                'departamento' => 'Valle del Cauca',
                'municipio' => 'Cali',
                'correo' => 'lucas.martinez@example.com',
            ],
            [
                'tipo_documento' => 'CC',
                'numero_documento' => '1045678901',
                'primer_nombre' => 'Ana',
                'segundo_nombre' => 'Lucía',
                'primer_apellido' => 'Hernández',
                'segundo_apellido' => 'García',
                'genero' => 'Femenino',
                'departamento' => 'Nariño',
                'municipio' => 'Pasto',
                'correo' => 'ana.hernandez@example.com',
            ],
            [
                'tipo_documento' => 'CC',
                'numero_documento' => '1056789012',
                'primer_nombre' => 'Javier',
                'segundo_nombre' => 'Alejandro',
                'primer_apellido' => 'Ramírez',
                'segundo_apellido' => 'Vargas',
                'genero' => 'Masculino',
                'departamento' => 'Tolima',
                'municipio' => 'Ibagué',
                'correo' => 'javier.ramirez@example.com',
            ],
        ];

        foreach ($pacientesData as $data) {
            $tipoDoc = TipoDocumento::where('codigo', $data['tipo_documento'])->first();
            $genero = Genero::where('nombre', $data['genero'])->first();
            $depto = Departamento::where('nombre', $data['departamento'])->first();
            $muni = Municipio::where('nombre', $data['municipio'])->first();

            if ($tipoDoc && $genero && $depto && $muni) {
                Paciente::create([
                    'tipo_documento_id' => $tipoDoc->id,
                    'numero_documento' => $data['numero_documento'],
                    'primer_nombre' => $data['primer_nombre'],
                    'segundo_nombre' => $data['segundo_nombre'] ?? null,
                    'primer_apellido' => $data['primer_apellido'],
                    'segundo_apellido' => $data['segundo_apellido'] ?? null,
                    'genero_id' => $genero->id,
                    'departamento_id' => $depto->id,
                    'municipio_id' => $muni->id,
                    'correo' => $data['correo'],
                ]);
            }
        }
    }
}
