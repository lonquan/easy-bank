<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Support;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;

class Logger
{
    protected MonologLogger $logger;

    public function __construct(string $path = '/tmp/easy-bank', int $days = 30)
    {
        $this->logger = new MonologLogger('EasyBank');
        $this->logger->pushHandler(new RotatingFileHandler("{$path}/logs/easy_bank.log", $days));
    }

    public function __call(string $name, array $arguments)
    {
        call_user_func_array([$this->logger, $name], $arguments);
    }
}