<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiPcDomainsEstimateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domains' => 'required|array|min:1',
            'currency' => 'required|in:USD,EUR,GBP,CNY,CHF,JPY'
        ];
    }
}
