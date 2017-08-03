<?php

namespace CashLog\Model;

use Doctrine\DBAL\Connection;

class Log
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function addLog($description)
    {
        $this->connection->insert('logs', [
            'description'   => $description,
            'ip_address'    => "INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')",
            'useragent'     => $_SERVER['HTTP_USER_AGENT']
        ]);
    }
}