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

    public function getOperationById($id)
    {
        return $this->connection->executeQuery("SELECT id, type, datetime, description, cash, balance FROM cashlog WHERE id = ?", [$id])->fetch();
    }

    public function updateOperationDescription($id, $description)
    {
        $this->connection->update('cashlog', ['description' => $description], ['id' => $id]);
    }

    public function removeOperation()
    {
        $this->connection->executeQuery("DELETE FROM cashlog ORDER BY id DESC LIMIT 1");
    }

    public function createOperation($type, $description, $cash)
    {
        switch ($type) {
            case 0:
                $this->connection->executeQuery('CALL payin(?, ?)', [
                    $description,
                    $cash
                ]);
            break;
            case 1:
                $this->connection->executeQuery('CALL payout(?, ?)', [
                    $description,
                    $cash
                ]);
            break;
        }
    }
}