<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use CashLog\Validator\ConfirmPasswordValidator;

class ConfirmPasswordValidatorServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['validator.confirmpassword'] = function () use ($app) {
            $validator = new ConfirmPasswordValidator();
            $validator->setDependencies($app['security.token_storage'], $app['security.encoder_factory'], $app['UserModel']);

            return $validator;
        };
    }

}