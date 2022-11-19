<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
        return [
            'image' => ['nullable'],
            'name' => ['sometimes', 'string', 'unique:plans,name,'.$this->plan->id],
            'limit_clients' => ['required', 'integer'],
            'limit_suppliers' => ['required', 'integer'],
            'limit_employees' => ['required', 'integer'],
            'limit_domains' => ['required', 'integer'],
            'limit_purchases' => ['required', 'integer'],
            'limit_invoices' => ['required', 'integer'],
            'features' => ['required', 'array'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $features = collect($this->features) ?? collect([]);

        $this->merge([
            'features' => $features->pluck('id')->toArray(),
        ]);
    }
}
