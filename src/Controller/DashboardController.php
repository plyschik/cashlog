<?php

namespace CashLog\Controller;

use CashLog\Form\OperationType;
use CashLog\Utility\OperationsPaginator;
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
        
        $paginator = new OperationsPaginator($this->app['db'], $request, getenv('ITEMS_PER_PAGE'));

        $logs = $this->app['OperationModel']->getOperations($paginator->getStart(), getenv('ITEMS_PER_PAGE'));

        return $this->app->render('dashboard/index.twig', [
            'logs'          => $logs,
            'form'          => $form->createView(),
            'pagination'    => [
                'availablePages'    => $paginator->getAvailablePages(),
                'currentPage'       => $paginator->getCurrentPage()
            ]
        ]);
    }
}