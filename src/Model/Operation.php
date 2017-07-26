<?php

namespace CashLog\Model;

use Doctrine\DBAL\Connection;

class Operation
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getOperations($start = 0, $limit = 10)
    {
        return $this->connection->fetchAll("SELECT id, type, datetime, description, cash, balance FROM cashlog ORDER BY id DESC LIMIT {$start}, {$limit}");
    }
}