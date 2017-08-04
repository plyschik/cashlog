<?php

namespace CashLog;

use Silex\Application;

class CashLogApplication extends Application
{
    use LoggerTrait;
    use Application\TwigTrait;
    use Application\SecurityTrait;
    use Application\UrlGeneratorTrait;

    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }
}