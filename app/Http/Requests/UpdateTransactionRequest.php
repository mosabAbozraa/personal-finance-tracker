<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'wallet_id'     => 'sometimes|exists:wallets,id',
            'category_id'   => 'sometimes|exists:categories,id',
            'amount'        => 'sometimes|numeric|min:0.01',
            'type'          => 'sometimes|in:income,expense',
            'date'          => 'sometimes|date',
            'notes'         => 'nullable|string|max:200'
        ];
    }
}
