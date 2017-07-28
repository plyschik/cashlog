<?php

namespace CashLog\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use CashLog\Model\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ValidPasswordValidator extends ConstraintValidator
{
    private $tokenStorage;
    private $encoderFactory;
    private $userModel;

    public function validate($value, Constraint $constraint)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $encoder = $this->encoderFactory->getEncoder($user);

        $isPasswordValid = $encoder->isPasswordValid($this->userModel->getPasswordByUsername($user->getUsername()), $value, $user->getSalt());

        if (!$isPasswordValid) {
            $this->context->addViolation($constraint->message);
        }
    }

    public function setDependencies(TokenStorageInterface $tokenStorage, EncoderFactoryInterface $encoderFactory, User $userModel)
    {
        $this->tokenStorage = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
        $this->userModel = $userModel;
    }
}