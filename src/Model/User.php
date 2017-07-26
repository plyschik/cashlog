<?php

namespace CashLog\Model;

use Doctrine\DBAL\Connection;

class User
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getPasswordByUsername($username)
    {
        return $this->connection->executeQuery("SELECT password FROM users WHERE username = ?", [$username])->fetchColumn();
    }
}