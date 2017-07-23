<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Response;

class DashboardController extends BaseController
{
    public function indexAction()
    {
        return $this->app->render('dashboard/index.twig');
    }
}