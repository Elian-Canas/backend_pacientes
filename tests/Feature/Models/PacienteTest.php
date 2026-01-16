<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PacienteTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Cargar SOLO los seeders de catálogos
        $this->seed([
            \Database\Seeders\TipoDocumentoSeeder::class,
            \Database\Seeders\GeneroSeeder::class,
            \Database\Seeders\DepartamentoSeeder::class,
            \Database\Seeders\MunicipioSeeder::class,
        ]);

        // Crear usuario de prueba
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Obtener token JWT
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->token = $response->json('access_token');
        $response->assertStatus(200);
    }

    #[Test]
    public function can_list_pacientes()
    {
        Paciente::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/pacientes');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function can_create_paciente()
    {
        // Usar IDs reales de los seeders (ajusta según tus datos)
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/pacientes', [
                'tipo_documento_id' => 1,
                'numero_documento' => '123456789',
                'primer_nombre' => 'Juan',
                'primer_apellido' => 'Pérez',
                'genero_id' => 1,
                'departamento_id' => 2,
                'municipio_id' => 3,
                'correo' => 'juan@example.com',
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['primer_nombre' => 'Juan']);

        $this->assertDatabaseHas('pacientes', [
            'numero_documento' => '123456789',
        ]);
    }

    #[Test]
    public function cannot_create_paciente_without_auth()
    {
        $response = $this->postJson('/api/pacientes', [
            'tipo_documento_id' => 1,
            'numero_documento' => '123456789',
            'primer_nombre' => 'Juan',
            'primer_apellido' => 'Pérez',
            'genero_id' => 1,
            'departamento_id' => 1,
            'municipio_id' => 1,
            'correo' => 'juan@example.com',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Token not provided']);
    }

    #[Test]
    public function cannot_create_paciente_with_invalid_data()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/pacientes', [
                'numero_documento' => '123',
                'correo' => 'invalid-email',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'primer_nombre',
                'primer_apellido',
                'genero_id',
                'departamento_id',
                'municipio_id'
            ]);
    }

    #[Test]
    public function can_search_pacientes()
    {
        Paciente::factory()->create([
            'primer_nombre' => 'Ana',
            'primer_apellido' => 'López',
            'correo' => 'ana@example.com',
            'tipo_documento_id' => 1,
            'genero_id' => 1,
            'departamento_id' => 1,
            'municipio_id' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/pacientes/buscar?q=Ana');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['primer_nombre' => 'Ana']);
    }

    #[Test]
    public function can_delete_paciente()
    {
        $paciente = Paciente::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/pacientes/{$paciente->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('pacientes', ['id' => $paciente->id]);
    }

    #[Test]
    public function public_catalogs_are_accessible()
    {
        $response = $this->getJson('/api/tipo-documentos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'nombre'] // Asume que cada item tiene id y nombre
            ]);
    }
}
