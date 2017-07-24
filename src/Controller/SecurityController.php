<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends BaseController
{
    public function signinAction(Request $request)
    {
        return $this->app->render('security/signin.twig', [
            'error'         => $this->app['security.last_error']($request),
            'last_username' => $this->app['session']->get('_security.last_username')
        ]);
    }

    public function signoutAction()
    {
    }
}