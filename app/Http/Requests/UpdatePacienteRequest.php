<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $paciente = $this->route('paciente');

        return [
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'numero_documento'  => [
                'required',
                'string',
                'max:20',
                Rule::unique('pacientes', 'numero_documento')->ignore($paciente),
            ],
            'primer_nombre'     => 'required|string|max:100',
            'segundo_nombre'    => 'nullable|string|max:100',
            'primer_apellido'   => 'required|string|max:100',
            'segundo_apellido'  => 'nullable|string|max:100',
            'genero_id'         => 'required|exists:generos,id',
            'departamento_id'   => 'required|exists:departamentos,id',
            'municipio_id'      => 'required|exists:municipios,id',
            'correo'            => [
                'required',
                'email',
                'max:255',
                Rule::unique('pacientes', 'correo')->ignore($paciente),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_documento_id.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required'  => 'El número de documento es obligatorio.',
            'primer_nombre.required'     => 'El primer nombre es obligatorio.',
            'primer_apellido.required'   => 'El primer apellido es obligatorio.',
            'genero_id.required'         => 'El género es obligatorio.',
            'departamento_id.required'   => 'El departamento es obligatorio.',
            'municipio_id.required'      => 'El municipio es obligatorio.',
            'correo.required'            => 'El correo electrónico es obligatorio.',

            'numero_documento.unique' => 'El número de documento ya está registrado.',
            'correo.unique'           => 'El correo electrónico ya está en uso.',

            'correo.email' => 'El correo debe ser una dirección de email válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_documento_id' => 'tipo de documento',
            'numero_documento'  => 'número de documento',
            'primer_nombre'     => 'primer nombre',
            'segundo_nombre'    => 'segundo nombre',
            'primer_apellido'   => 'primer apellido',
            'segundo_apellido'  => 'segundo apellido',
            'genero_id'         => 'género',
            'departamento_id'   => 'departamento',
            'municipio_id'      => 'municipio',
            'correo'            => 'correo electrónico',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => strtolower(trim($this->correo)),
            'numero_documento' => trim($this->numero_documento),
            'primer_nombre' => trim(ucwords(strtolower($this->primer_nombre))),
            'primer_apellido' => trim(ucwords(strtolower($this->primer_apellido))),
            'segundo_nombre' => $this->segundo_nombre ? trim(ucwords(strtolower($this->segundo_nombre))) : null,
            'segundo_apellido' => $this->segundo_apellido ? trim(ucwords(strtolower($this->segundo_apellido))) : null,
        ]);
    }

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
