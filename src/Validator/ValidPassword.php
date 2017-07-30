<?php

namespace CashLog\Validator;

use Symfony\Component\Validator\Constraint;

class ValidPassword extends Constraint
{
    public $message = 'constraints.ValidPassword.message';

    public function validatedBy()
    {
        return 'validator.valid_password';
    }
}