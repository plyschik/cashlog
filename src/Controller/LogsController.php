<?php

namespace CashLog\Controller;

use CashLog\Form\ConfirmType;
use CashLog\Form\OperationType;
use CashLog\Form\OperationEditType;
use CashLog\Utility\OperationsPaginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class LogsController extends BaseController
{
    public function indexAction(Request $request)
    {
        $form = $this->app['form.factory']->create(OperationType::class)->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $this->app['OperationModel']->createOperation($data['type'], $data['description'], $data['cash']);

            $this->app['session']->getFlashBag()->add('success', 'Operacja zakończyła się sukcesem!');

            return $this->app->redirect($this->app->url('logs.index'));
        }
        
        $paginator = new OperationsPaginator($this->app['db'], $request->query->get('page'), getenv('ITEMS_PER_PAGE'));

        $logs = $this->app['OperationModel']->getOperations($paginator->getStart(), getenv('ITEMS_PER_PAGE'));

        return $this->app->render('logs/index.twig', [
            'logs'          => $logs,
            'form'          => $form->createView(),
            'pagination'    => [
                'availablePages'    => $paginator->getAvailablePages(),
                'currentPage'       => $paginator->getCurrentPage()
            ]
        ]);
    }

    public function editAction($id, Request $request)
    {
        $data = $this->app['OperationModel']->getOperationById($id);

        if (!$data) {
            return $this->app->redirect($this->app->url('logs'));
        }

        $form = $this->app['form.factory']->create(OperationEditType::class, $data)->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $this->app['OperationModel']->updateOperationDescription($id, $data['description']);

            $this->app['session']->getFlashBag()->add('success', 'Zapis operacji został poprawnie zaktualizowany!');

            return $this->app->redirect($this->app->url('logs.index'));
        }

        return $this->app->render('logs/edit.twig', [
            'form'  => $form->createView()
        ]);
    }

    public function removeAction(Request $request)
    {
        $form = $this->app['form.factory']->create(ConfirmType::class)->handleRequest($request);

        if ($form->isValid()) {
            $this->app['OperationModel']->removeOperation();

            $this->app['session']->getFlashBag()->add('success', 'Zapis operacji został poprawnie usuniętusunięty.');

            return $this->app->redirect($this->app->url('logs.index'));
        }

        return $this->app->render('logs/remove.twig', [
            'form' => $form->createView()
        ]);
    }
}