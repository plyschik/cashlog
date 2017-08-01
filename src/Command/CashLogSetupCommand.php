<?php

namespace CashLog\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class CashLogSetupCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cashlog:setup')
            ->setDescription('CashLog application setup with user password set.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $schema = new Schema();

        $users = $schema->createTable('users');
        $users->addColumn('id', 'smallint', [
            'length' => 3,
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $users->addColumn('username', 'string', [
            'length' => 32
        ]);
        $users->addColumn('password', 'string', [
            'length' => 64
        ]);
        $users->addColumn('roles', 'text');
        $users->setPrimaryKey(['id']);
        $users->addUniqueIndex(['username']);

        $signinFailedAttempts = $schema->createTable('signin_failed_attempts');
        $signinFailedAttempts->addColumn('datetime', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $signinFailedAttempts->addColumn('ip_address', 'integer', [
            'unsigned' => true,
            'length' => 4
        ]);
        $signinFailedAttempts->addColumn('useragent', 'string', [
            'length' => 255
        ]);

        $cashlog = $schema->createTable('cashlog');
        $cashlog->addColumn('id', 'smallint', [
            'length' => 5,
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $cashlog->addColumn('type', 'smallint', [
            'length' => 3,
            'unsigned' => true
        ]);
        $cashlog->addColumn('datetime', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $cashlog->addColumn('description', 'string', [
            'length' => 64
        ]);
        $cashlog->addColumn('cash', 'decimal', [
            'precision' => 8,
            'scale'     => 2
        ]);
        $cashlog->addColumn('balance', 'decimal', [
            'precision' => 8,
            'scale'     => 2
        ]);
        $cashlog->setPrimaryKey(['id']);

        $queries = $schema->toSql($app['db']->getDatabasePlatform());

        foreach ($queries as $query) {
            $app['db']->executeQuery($query);
        }

        $helper = $this->getHelper('question');

        $usernameQuestion = new Question('Podaj nazwę użytkownika: ');
        
        $passwordQuestion = new Question('Podaj hasło: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $app['db']->insert('users', [
            'username'  => $helper->ask($input, $output, $usernameQuestion),
            'password'  => (new BCryptPasswordEncoder(13))->encodePassword($helper->ask($input, $output, $passwordQuestion), ''),
            'roles'     => 'ROLE_USER'
        ]);

        $output->write("<info>Tabele zostały utworzone.</info>");
    }
}