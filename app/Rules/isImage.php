<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class isImage implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!is_array(getimagesize($value))) {
            $fail('The :attribute is only allow image file.');

        }
    }
}
