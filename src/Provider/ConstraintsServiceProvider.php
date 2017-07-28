<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use CashLog\Validator\ValidPasswordValidator;

class ConstraintsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['validator.valid_password'] = function () use ($app) {
            $validator = new ValidPasswordValidator();
            $validator->setDependencies($app['security.token_storage'], $app['security.encoder_factory'], $app['UserModel']);

            return $validator;
        };
    }

}