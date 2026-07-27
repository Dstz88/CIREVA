<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        // Only Admin can verify the transaction
        return $transaction ? $this->user()->can('verify', $transaction) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'is_success' => 'required|boolean',
            'notes' => 'nullable|string',
        ];
    }
}
