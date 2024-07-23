<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Support;

use Illuminate\Support\Collection;

class Config extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public function getDefualt(): string
    {
        return $this->get('default');
    }

    public function isEnabledDebug(): bool
    {
        return data_get($this->items, 'debug.enabled', false);
    }

    public function getRuntimePath(string $defualt = '/tmp/easy-bank'): ?string
    {
        return data_get($this->items, 'debug.path', $defualt);
    }

    public function getMaxLogFiles(int $defualt = 30): int
    {
        return data_get($this->items, 'debug.days', $defualt);
    }
}