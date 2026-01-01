<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            
            // 🆕 AGREGAR ESTOS CAMPOS
            'edad' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:120'],
            'sexo' => ['sometimes', 'nullable', 'in:Masculino,Femenino,Otro'],
            'especialidad' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}