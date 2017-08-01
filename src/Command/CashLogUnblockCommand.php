<?php

namespace CashLog\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CashLogUnblockCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cashlog:unblock')
            ->setDescription('Signin form unblock.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $app['UserModel']->truncateFailedAttempts();

        $output->write("<info>Formularz został odblokowany.</info>");
    }
}