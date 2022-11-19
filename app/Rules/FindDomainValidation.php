<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Stancl\Tenancy\Database\Models\Domain;

class FindDomainValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domain = $value;

        return Domain::where('domain', '=', $domain)->count() == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not found.';
    }
}
