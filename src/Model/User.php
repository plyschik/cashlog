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

    public function insertFailedAttempt($remoteAddr, $httpUserAgent)
    {
        $this->connection->insert('signin_failed_attempts', [
            'ip_address'    => "INET_ATON('" . $remoteAddr . "')",
            'useragent'     => $httpUserAgent
        ]);
    }

    public function truncateFailedAttempts()
    {
        $this->connection->executeQuery('TRUNCATE TABLE signin_failed_attempts;');
    }

    public function getAvailableSigninAttempts($signinAttempts)
    {
        return $this->connection->fetchColumn("SELECT " . $signinAttempts . " - (SELECT COUNT(*) FROM signin_failed_attempts)");
    }
}