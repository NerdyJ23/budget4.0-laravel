<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;
use App\Clients\Users\UserClient;
class ReceiptCategoryPatchRequest extends FormRequest
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
			'name' => 'nullable|min:1|max:255'
        ];
    }
}
