<?php

namespace CashLog\Validator;

use Symfony\Component\Validator\Constraint;

class ValidPassword extends Constraint
{
    public $message = 'Podane hasło jest niepoprawne.';

    public function validatedBy()
    {
        return 'validator.valid_password';
    }
}