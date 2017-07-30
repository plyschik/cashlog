<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    public function signinAction(Request $request)
    {
        $availableSigninAttempts = $this->app['availableSigninAttempts'];

        if ($availableSigninAttempts > 0) {
            return $this->app->render('security/signin.twig', [
                'error'                     => $this->app['security.last_error']($request),
                'last_username'             => $this->app['session']->get('_security.last_username'),
                'availableSigninAttempts'   => $availableSigninAttempts
            ]);
        } else {
            return $this->app->render('security/signinblock.twig');
        }
    }

    public function unblockAction($key)
    {
        if ($key == getenv('SIGNIN_UNBLOCK_KEY')) {
            $this->app['UserModel']->truncateFailedAttempts();
        }

        return $this->app->redirect($this->app->url('security.signin'));
    }
}