<?php

namespace App\Http\Requests;

use App\Entities\Investment\DTO\InvestmentCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvestmentRequest extends FormRequest
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
            'amount'            => 'required|numeric|gt:0',
            'guest_identifier'  => 'required|string|max:255'
        ];
    }

    public function validatedDTO(): InvestmentCreateDTO
    {
        return InvestmentCreateDTO::fromArray($this->validated());
    }
}
