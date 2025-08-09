<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'code'  => 'required|string|max:50|unique:locations,code',
            'name'  => 'required|string|max:150',
            'image' => 'required|url',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code'  => isset($this->code) ? strtoupper(trim($this->code)) : null,
            'name'  => isset($this->name) ? ucwords(strtolower(trim($this->name))) : null,
            'image' => isset($this->image) ? trim($this->image) : null
        ]);
    }
}
