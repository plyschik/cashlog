<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Response;

class DashboardController extends BaseController
{
    public function indexAction()
    {
        $logs = $this->app['db']->fetchAll('SELECT type, datetime, description, cash, balance FROM cashlog ORDER BY id DESC');

        return $this->app->render('dashboard/index.twig', [
            'logs' => $logs
        ]);
    }
}