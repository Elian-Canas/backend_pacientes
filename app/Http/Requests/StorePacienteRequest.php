<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePacienteRequest extends FormRequest
{
    /**
     * Indica si el usuario está autorizado para hacer esta solicitud.
     *
     * En APIs con JWT, normalmente asumimos que si pasó el middleware auth,
     * ya está autorizado. Pero puedes agregar lógica adicional si es necesario.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'numero_documento' => 'required|string|max:20|unique:pacientes,numero_documento',
            'primer_nombre'      => 'required|string|max:100',
            'segundo_nombre'     => 'nullable|string|max:100',
            'primer_apellido'    => 'required|string|max:100',
            'segundo_apellido'   => 'nullable|string|max:100',
            'genero_id'          => 'required|exists:generos,id',
            'departamento_id'    => 'required|exists:departamentos,id',
            'municipio_id'       => 'required|exists:municipios,id',
            'correo'             => 'required|email|max:255|unique:pacientes,correo',
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            // Reglas "required"
            'tipo_documento_id.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required'  => 'El número de documento es obligatorio.',
            'primer_nombre.required'     => 'El primer nombre es obligatorio.',
            'primer_apellido.required'   => 'El primer apellido es obligatorio.',
            'genero_id.required'         => 'El género es obligatorio.',
            'departamento_id.required'   => 'El departamento es obligatorio.',
            'municipio_id.required'      => 'El municipio es obligatorio.',
            'correo.required'            => 'El correo electrónico es obligatorio.',

            // Reglas "exists"
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no es válido.',
            'genero_id.exists'         => 'El género seleccionado no es válido.',
            'departamento_id.exists'   => 'El departamento seleccionado no es válido.',
            'municipio_id.exists'      => 'El municipio seleccionado no es válido.',

            // Reglas "unique"
            'numero_documento.unique'  => 'El número de documento ya está registrado.',
            'correo.unique'            => 'El correo electrónico ya está en uso.',

            // Reglas "email"
            'correo.email'             => 'El correo debe ser una dirección de email válida.',
        ];
    }

    /**
     * Opcional: Formatear los datos antes de la validación (ej. trim).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => strtolower(trim($this->correo)),
            'numero_documento' => trim($this->numero_documento),
            'primer_nombre' => trim(ucwords(strtolower($this->primer_nombre))),
            'primer_apellido' => trim(ucwords(strtolower($this->primer_apellido))),
            // Aplica formato similar a otros campos si lo deseas
        ]);
    }

    /**
     * Opcional: Personalizar la respuesta de error (si quieres JSON consistente).
     * Esto asegura que incluso errores de validación devuelvan JSON en APIs.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
