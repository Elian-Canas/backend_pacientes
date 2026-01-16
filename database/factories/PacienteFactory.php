<?php

namespace Database\Factories;

use App\Models\Departamento;
use App\Models\Genero;
use App\Models\Municipio;
use App\Models\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo_documento_id' => TipoDocumento::inRandomOrder()->first()->id,
            'numero_documento' => $this->faker->unique()->numerify('########'),
            'primer_nombre' => $this->faker->firstName(),
            'segundo_nombre' => $this->faker->optional()->firstName(),
            'primer_apellido' => $this->faker->lastName(),
            'segundo_apellido' => $this->faker->optional()->lastName(),
            'genero_id' => Genero::inRandomOrder()->first()->id,
            'departamento_id' => Departamento::inRandomOrder()->first()->id,
            'municipio_id' => fn(array $attributes) => Municipio::where('departamento_id', $attributes['departamento_id'])->inRandomOrder()->first()->id,
            'correo' => $this->faker->unique()->safeEmail(),
        ];
    }
}
