<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends BaseRequest
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
        $user = $this->user();

        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|min:3|unique:users,email,'.$user->id,
            'currentPassword' => $this->newPassword != null ? ['required', 'string', 'min:8', function ($attribute, $value, $fail) use ($user) {
                if (! Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }] : 'nullable',

            'newPassword' => $this->currentPassword != null ? 'required|string|min:8|required_with:confirmPassword' : 'nullable',
            'confirmPassword' => $this->newPassword != null ? 'required|string|min:8|same:newPassword' : 'nullable',
        ];

    }
}
