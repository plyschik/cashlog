<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Response;

class SecurityController extends BaseController
{
    public function signinAction()
    {
        return new Response('Signin form.');
    }

    public function signoutAction()
    {
        return new Response('Signout.');
    }
}