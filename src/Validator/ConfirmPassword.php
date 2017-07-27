<?php

namespace CashLog\Validator;

use Symfony\Component\Validator\Constraint;

class ConfirmPassword extends Constraint
{
    public $message = 'Podane hasło jest niepoprawne.';

    public function validatedBy()
    {
        return 'validator.confirmpassword';
    }
}