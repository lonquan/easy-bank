<?php
declare(strict_types=1);

namespace EasyBank\Traits;

use EasyBank\Support\Logger;

trait UseLogger
{
    protected ?Logger $logger = null;

    protected function setLogger(?Logger $logger): void
    {
        $this->logger = $logger;
    }

    protected function getLogger(): Logger
    {
        return $this->logger;
    }

    protected function hasLogger(): bool
    {
        return !is_null($this->logger);
    }
}