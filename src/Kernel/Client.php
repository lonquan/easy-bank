<?php
declare(strict_types=1);

namespace EasyBank\Kernel;

use EasyBank\Contracts\BankInterface;
use EasyBank\Middleware\HttpClientMiddleware;
use EasyBank\Support\Logger;
use EasyBank\Traits\UseHttpClient;
use EasyBank\Traits\UseLogger;
use EasyBank\Traits\UseMerchant;
use GuzzleHttp\Client as Http;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

abstract class Client implements BankInterface
{
    use UseHttpClient, UseLogger, UseMerchant;

    protected const DEFAULT_TIMEOUT = 5.0;

    public function __construct(array $http, array $merchant, Logger $logger = null)
    {
        $this->withMerchant($merchant);
        $this->setLogger($logger);
        $this->setThrowException($http['throw'] ?? true);
        $this->createHttp($http['base_uri'] ?? null, $http['timeout'] ?? static::DEFAULT_TIMEOUT);
    }

    protected function createHttp(string $baseUri, float $timeout): void
    {
        $this->http = new Http([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
            'handler' => $this->withHttpHandleStacks(),
        ]);
    }

    protected function withHttpHandleStacks(): ?HandlerStack
    {
        if ($this->hasLogger()) {
            $stack = new HandlerStack();
            $stack->setHandler(new CurlHandler());
            $stack->push(new HttpClientMiddleware($this->getName(), $this->getLogger()));

            return $stack;
        }

        return null;
    }

}