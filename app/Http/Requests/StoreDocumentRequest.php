<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title'            => ['required', 'string', 'max:255'],
            'department_ids'   => ['required', 'array', 'min:1'],
            'department_ids.*' => ['exists:departments,id'],
            'file'             => ['required', 'file', 'mimes:pdf', 'max:10240'], // 10MB
            'note'             => ['nullable', 'string', 'max:1000'],
            'change_type'      => ['required', 'in:content,validity'],
            'start_date'       => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'document_type_id.required' => 'Selecciona un tipo de documento.',
            'document_type_id.exists'   => 'El tipo de documento no es válido.',
            'title.required'            => 'El título es obligatorio.',
            'title.unique'              => 'Ya existe un documento con ese título en este tipo.',
            'department_ids.required'   => 'Asigna al menos un departamento.',
            'department_ids.min'        => 'Asigna al menos un departamento.',
            'file.required'             => 'El archivo PDF es obligatorio.',
            'file.mimes'                => 'Solo se permiten archivos PDF.',
            'file.max'                  => 'El archivo no puede superar 10MB.',
            'start_date.required'       => 'La fecha de vigencia es obligatoria.',
        ];
    }
}
