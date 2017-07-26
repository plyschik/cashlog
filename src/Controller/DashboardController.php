<?php

namespace CashLog\Controller;

use CashLog\Form\ConfirmType;
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

            $this->app['OperationModel']->createOperation($data['type'], $data['description'], $data['cash']);

            $this->app['session']->getFlashBag()->add('success', 'Operacja zakończyła się sukcesem!');

            return $this->app->redirect($this->app->url('dashboard'));
        }
        
        $paginator = new OperationsPaginator($this->app['db'], $request->query->get('page'), getenv('ITEMS_PER_PAGE'));

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

    public function removeAction($id, Request $request)
    {
        $form = $this->app['form.factory']->create(ConfirmType::class)->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();


            $user = $this->app['security.token_storage']->getToken()->getUser();
            $encoder = $this->app['security.encoder_factory']->getEncoder($user);

            $isValid = $encoder->isPasswordValid($this->app['UserModel']->getPasswordByUsername($user->getUsername()), $data['password'], $user->getSalt());

            if ($isValid) {
                $this->app['OperationModel']->removeOperation($id);

                $this->app['session']->getFlashBag()->add('success', 'Zapis operacji został poprawnie usuniętusunięty!');

                return $this->app->redirect($this->app->url('dashboard'));
            } else {
                $this->app['session']->getFlashBag()->add('error', 'Podane hasło jest niepoprawne!');

                return $this->app->redirect($request->headers->get('referer'));
            }
        }

        return $this->app->render('dashboard/remove.twig', [
            'form' => $form->createView()
        ]);
    }
}