<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', (new Password)->length(1), 'confirmed'];
    }

    protected function passwordRulesNullable(): array
    {
        return ['nullable', 'string', (new Password)->length(1), 'confirmed'];
    }
}
