<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;
use App\Models\Client;

class UpdateClientRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $slug = $this->route('client');

        $client = Client::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20|min:3',
            'email' => 'nullable|email|max:255|min:3|unique:clients,email,'.$client->id,
            'companyName' => 'nullable|string|max:100|min:2',
            'address' => 'nullable|string|max:255',
        ];
    }
}
