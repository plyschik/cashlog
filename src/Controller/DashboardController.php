<?php

namespace CashLog\Controller;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends BaseController
{
    public function indexAction(Request $request)
    {
        $form = $this->app['form.factory']->createBuilder()
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'WPŁATA' => 0,
                    'WYPŁATA' => 1
                ],
                'constraints' => new Assert\Choice([
                    'choices' => [0, 1]
                ])
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Opis jest polem wymaganym.'
                    ])
                ]
            ])
            ->add('cash', MoneyType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Kwota jest polem wymaganym.'
                    ])
                ],
                'currency' => ''
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            switch ($data['type']) {
                case 0:
                    $this->app['db']->executeQuery('CALL payin(?, ?)', [
                        $data['description'],
                        $data['cash']
                    ]);
                break;
                case 1:
                    $this->app['db']->executeQuery('CALL payout(?, ?)', [
                        $data['description'],
                        $data['cash']
                    ]);
                break;
            }

            $this->app['session']->getFlashBag()->add('success', 'Operacja zakończyła się sukcesem!');

            return $this->app->redirect($this->app->url('dashboard'));
        }

        $logs = $this->app['db']->fetchAll('SELECT type, datetime, description, cash, balance FROM cashlog ORDER BY id DESC');

        return $this->app->render('dashboard/index.twig', [
            'logs' => $logs,
            'form' => $form->createView()
        ]);
    }
}