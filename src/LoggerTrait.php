<?php

namespace CashLog;

trait LoggerTrait
{
    public function log($description)
    {
        return $this['cashlog.logger']->log($description);
    }
}