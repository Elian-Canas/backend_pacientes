<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PacienteController extends Controller
{

    /**
     * Relaciones a cargar siempre.
     */
    protected array $relations = [
        'tipoDocumento',
        'genero',
        'departamento',
        'municipio'
    ];

    /**
     * Listar todos los pacientes con filtros opcionales por nombre, apellido, correo, etc.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Paciente::with($this->relations);

        // Filtros dinámicos
        if ($request->filled('numero_documento')) {
            $query->where('numero_documento', 'LIKE', "%{$request->input('numero_documento')}%");
        }
        if ($request->filled('primer_nombre')) {
            $query->where('primer_nombre', 'LIKE', "%{$request->input('primer_nombre')}%");
        }
        if ($request->filled('segundo_nombre')) {
            $query->where('segundo_nombre', 'LIKE', "%{$request->input('segundo_nombre')}%");
        }
        if ($request->filled('primer_apellido')) {
            $query->where('primer_apellido', 'LIKE', "%{$request->input('primer_apellido')}%");
        }
        if ($request->filled('segundo_apellido')) {
            $query->where('segundo_apellido', 'LIKE', "%{$request->input('segundo_apellido')}%");
        }
        if ($request->filled('correo')) {
            $query->where('correo', 'LIKE', "%{$request->input('correo')}%");
        }
        // Puedes agregar más filtros según tus necesidades

        $query->orderBy('id', 'desc');

        // Paginación
        $perPage = $request->input('limit', 10);
        $pacientes = $query->paginate($perPage);
        return response()->json($pacientes);
    }

    /**
     * Crear un nuevo paciente.
     */
    public function store(StorePacienteRequest $request): JsonResponse
    {
        try {
            $paciente = Paciente::create($request->validated());
            return response()->json($paciente, 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al crear el paciente',
                'error' => app()->environment('local') ? $th->getMessage() : null
            ], 500);
        }
    }

    /**
     * Mostrar un paciente específico.
     */
    public function show(Paciente $paciente): JsonResponse
    {
        $paciente->load($this->relations);
        return response()->json($paciente);
    }

    /**
     * Actualizar un paciente.
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente): JsonResponse
    {
        $paciente->update($request->validated());
        return response()->json($paciente);
    }

    /**
     * Eliminar un paciente.
     */
    public function destroy(Paciente $paciente): JsonResponse
    {
        $paciente->delete();
        return response()->json(null, 204);
    }
}
