<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:50',
            'currency'  => 'required|in:SYP,USD,EUR',
            'low_balance_threshold' => 'nullable|numeric|min:0',
        ];
    }
}
