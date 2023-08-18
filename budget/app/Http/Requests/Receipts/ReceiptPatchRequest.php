<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;
use App\Clients\Users\UserClient;
class ReceiptPatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
		$token = $this->cookies->get('token');
		return $token != null && UserClient::getByToken(token: $token) != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
			'name' => 'nullable|max:100',
			'location' => 'nullable|max:30',
			'reference' => 'nullable|max:60',
			'date' => 'nullable|date',
			'items' => 'nullable|json'
        ];
    }
}
