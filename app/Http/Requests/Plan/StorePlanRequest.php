<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'image' => ['required'],
            'name' => ['required', 'string', 'unique:plans,name'],
            'amount' => ['required', 'numeric'],
            'currency' => ['required', 'string'],
            'interval' => [
                function ($attribute, $value, $fail) {
                    //day, week, month or year
                    $interval_data = ['day', 'week', 'month', 'year'];
                    // If data is not in interval_data array it fails with a message
                    if (! in_array($value, $interval_data)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
            'description' => ['sometimes', 'max:22'],
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
