<?php

namespace CashLog\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DashboardController extends BaseController
{
    public function indexAction(Request $request)
    {
        $form = $this->app['form.factory']->createBuilder()
            ->add('type', ChoiceType::class, [
                'label' => false,
                'invalid_message' => 'Wybrany typ operacji jest niepoprawny.',
                'choices' => [
                    'WPŁATA' => 0,
                    'WYPŁATA' => 1
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Wybrany typ operacji nie istnieje.'
                    ]),
                    new Assert\Choice([
                        'choices' => [0, 1],
                        'message' => 'Wybierz poprawny typ operacji.'
                    ])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Opis jest polem wymaganym.'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 64,
                        'minMessage' => 'Opis musi zawierać conajmniej 8 znaków.',
                        'maxMessage' => 'Opis może mieć maksymalnie 64 znaki.'
                    ])
                ]
            ])
            ->add('cash', TextType::class, [
                'label' => false,
                'invalid_message' => 'Wpisana kwota jest niepoprawna.',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Kwota jest polem wymaganym.'
                    ]),
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'Kwota powinna być liczbą większą od zera.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{1,6}(?:\.[0-9]{0,2})?$/',
                        'message' => 'Wpisana kwota nie pasuje do wzoru. Kropka zamiast przecinka.'
                    ])
                ]
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
        
        $availablePages = ceil($this->app['db']->fetchColumn('SELECT COUNT(id) FROM cashlog') / getenv('ITEMS_PER_PAGE'));

        $currentPage = ($request->query->get('page') > 0 && $request->query->get('page') <= $availablePages) ? $request->query->get('page') : 1;

        $start = ($currentPage > 1) ? $currentPage * getenv('ITEMS_PER_PAGE') - getenv('ITEMS_PER_PAGE') : 0;

        $logs = $this->app['db']->fetchAll("SELECT id, type, datetime, description, cash, balance FROM cashlog ORDER BY id DESC LIMIT " . $start . ", " . getenv('ITEMS_PER_PAGE'));

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