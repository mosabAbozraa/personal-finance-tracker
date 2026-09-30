<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RecurrningTransactionRequest extends FormRequest
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
            'wallet_id'     => ['required', 'exists:wallets,id'],
            'category_id'   => ['required', 'exists:categories,id'],
            'amount'        => ['required', 'numeric', 'min:0.1'],
            'type'          => ['required', 'in:income,expense'],
            'notes'         => ['nullable', 'string', 'max:100'],
            'frequency'     => ['required', 'in:daily,weekly,monthly'],
            'next_run_at'   => ['sometimes', 'date']
        ];
    }
}
