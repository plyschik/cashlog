<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Response;

class DashboardController extends BaseController
{
    public function indexAction()
    {
        return new Response('Dashboard index.');
    }
}