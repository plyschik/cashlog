<?php

namespace CashLog\Controller;

use CashLog\Form\OperationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DashboardController extends BaseController
{
    public function indexAction(Request $request)
    {
        $form = $this->app['form.factory']->create(OperationType::class)->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $this->app['OperationModel']->addOperation($data['type'], $data['description'], $data['cash']);
            
            $this->app['session']->getFlashBag()->add('success', 'Operacja zakończyła się sukcesem!');

            return $this->app->redirect($this->app->url('dashboard'));
        }
        
        $availablePages = ceil($this->app['db']->fetchColumn('SELECT COUNT(id) FROM cashlog') / getenv('ITEMS_PER_PAGE'));

        $currentPage = ($request->query->get('page') > 0 && $request->query->get('page') <= $availablePages) ? $request->query->get('page') : 1;

        $start = ($currentPage > 1) ? $currentPage * getenv('ITEMS_PER_PAGE') - getenv('ITEMS_PER_PAGE') : 0;

        $logs = $this->app['OperationModel']->getOperations($start, getenv('ITEMS_PER_PAGE'));

        return $this->app->render('dashboard/index.twig', [
            'logs'          => $logs,
            'form'          => $form->createView(),
            'pagination'    => [
                'availablePages'    => $availablePages,
                'currentPage'       => $currentPage
            ]
        ]);
    }
}