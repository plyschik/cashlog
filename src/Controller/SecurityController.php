<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Response;

class SecurityController extends BaseController
{
    public function signinAction()
    {
        return $this->app->render('security/signin.twig');
    }

    public function signoutAction()
    {
        return new Response('Signout.');
    }
}