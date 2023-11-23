<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'serial_number' => 'required|string|unique:locations',
            'name' => 'required|string',
            'ip_address' => 'required|ip',
            'devices' => 'array|max:10', // Restrict to at most 10 devices
            'devices.*' => 'exists:devices,id', // Ensure each device exists
        ];
    }
}
