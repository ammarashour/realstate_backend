<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Closure;

class SepcialCharacter implements Rule
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

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if(preg_match('/^[a-zA-Z0-9]+$/', $value)){
            $fail('The :attribute cannot be set with special character.');
        }

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
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
