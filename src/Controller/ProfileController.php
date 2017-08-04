<?php

namespace CashLog\Controller;

use CashLog\Form\PasswordChangeType;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    public function changePasswordAction(Request $request)
    {
        $form = $this->app['form.factory']->create(PasswordChangeType::class)->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $this->app['db']->update('users', [
                'password' => $this->app->encodePassword($this->app['user'], $data['newPassword'])
            ], [
                'username' => $this->app['user']->getUsername()
            ]);

            $this->app['dispatcher']->dispatch('application.passwordChange');

            return $this->app->redirect($this->app->url('signout'));
        }

        return $this->app->render('profile/changepassword.twig', [
            'form' => $form->createView()
        ]);
    }
}