<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAulaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        // El middleware ya verifica que sea admin, así que retornamos true
        return true;
    }

    /**
     * Reglas de validación para crear un aula.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100|unique:aulas,nombre',
            'edificio' => 'required|string|max:100',
            'capacidad_alumnos' => 'required|integer|min:1|max:200',
            'capacidad_equipos' => 'required|integer|min:0|max:200',
            'activo' => 'boolean',
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del aula es obligatorio.',
            'nombre.unique' => 'Ya existe un aula con este nombre.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'edificio.required' => 'El edificio es obligatorio.',
            'edificio.max' => 'El edificio no puede tener más de 100 caracteres.',
            'capacidad_alumnos.required' => 'La capacidad de alumnos es obligatoria.',
            'capacidad_alumnos.integer' => 'La capacidad de alumnos debe ser un número entero.',
            'capacidad_alumnos.min' => 'La capacidad de alumnos debe ser al menos 1.',
            'capacidad_alumnos.max' => 'La capacidad de alumnos no puede ser mayor a 200.',
            'capacidad_equipos.required' => 'La capacidad de equipos es obligatoria.',
            'capacidad_equipos.integer' => 'La capacidad de equipos debe ser un número entero.',
            'capacidad_equipos.min' => 'La capacidad de equipos debe ser al menos 0.',
            'capacidad_equipos.max' => 'La capacidad de equipos no puede ser mayor a 200.',
        ];
    }

}
