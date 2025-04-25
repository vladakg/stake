<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedCampaignRequest extends FormRequest
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
            'sortProperty'              => 'sometimes|in:id,name,percentage_raised,target_amount,number_of_investors,created_at,updated_at',
            'sortDirection'             => 'sometimes|in:asc,desc',
            'page'                      => 'integer|min:1',
            'size'                      => 'integer|min:1',
            'search'                    => 'string|nullable',
            'number_of_investors'       => 'array|nullable',
            'number_of_investors.min'   => 'integer|nullable',
            'number_of_investors.max'   => 'integer|nullable',
            'target_amount'             => 'array|nullable',
            'target_amount.min'         => 'numeric|nullable',
            'target_amount.max'         => 'numeric|nullable',
            'percentage_raised'         => 'array|nullable',
            'percentage_raised.min'     => 'numeric|nullable',
            'percentage_raised.max'     => 'numeric|nullable',
        ];
    }
}
