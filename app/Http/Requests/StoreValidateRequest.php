<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Http\FormRequest;

class StoreValidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'url_destino' => [
                'required', 'url', 'regex:/^https:/',
                function ($attribute, $value, $fail) {
                    // Verificar se a URL de destino aponta para este mesmo projeto
                    $url_destino = parse_url($value, PHP_URL_HOST);
                    $url = parse_url(url('/'), PHP_URL_HOST);

                    if ($url_destino && $url_destino === $url) {
                        $fail('A URL de destino nao pode apontar para o proprio projeto.');
                    }
                    try {
                        $response = Http::withoutVerifying()->get($value);
                        if (!$response->successful()) {
                            $fail('A URL de destino nao retornou um status 200.');
                        }
                    } catch (\Exception $e) {
                        $fail('A URL de destino nao retornou um status 200.');
                    }
                }
            ],
        ];

        return $rules;
    }
}
