<?php

namespace CashLog\Controller;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends BaseController
{
    public function indexAction(Request $request)
    {
        $form = $this->app['form.factory']->createBuilder()
            ->add('datetime', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Data jest polem wymaganym.'
                    ])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Opis jest polem wymaganym.'
                    ])
                ]
            ])
            ->add('cash', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Kwota jest polem wymaganym.'
                    ])
                ]
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isValid()) {
        }

        $logs = $this->app['db']->fetchAll('SELECT type, datetime, description, cash, balance FROM cashlog ORDER BY id DESC');

        return $this->app->render('dashboard/index.twig', [
            'logs' => $logs,
            'form' => $form->createView()
        ]);
    }
}