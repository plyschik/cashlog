<?php

namespace CashLog\Utility;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;

class OperationsPaginator
{
    private $connection;
    private $requestPage;
    private $limit;
    private $currentPage;
    private $availablePages;

    public function __construct(Connection $connection, $requestPage, $limit)
    {
        $this->connection = $connection;
        $this->requestPage = $requestPage;
        $this->limit = $limit;

        $this->calculateAvailablePages();
        $this->calculateCurrentPage();
    }

    public function calculateAvailablePages()
    {
        $this->availablePages = ceil($this->connection->fetchColumn('SELECT COUNT(id) FROM cashlog') / $this->limit);
    }

    public function calculateCurrentPage()
    {
        $this->currentPage = ($this->requestPage > 0 && $this->requestPage <= $this->getAvailablePages()) ? $this->requestPage : 1;
    }

    public function getStart()
    {
        return ($this->getCurrentPage() > 1) ? $this->getCurrentPage() * $this->limit - $this->limit : 0;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getAvailablePages()
    {
        return $this->availablePages;
    }
}