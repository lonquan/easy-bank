<?php
declare(strict_types=1);

namespace AntCool\EasyBank;

use AntCool\EasyBank\Exceptions\InvalidConfigException;
use AntCool\EasyBank\Kernel\Client;
use AntCool\EasyBank\Kernel\Server;
use AntCool\EasyBank\Support\Config;
use AntCool\EasyBank\Support\Logger;

class EasyBank
{
    const SDK_VERSION = '0.0.1';

    protected Config $config;

    protected ?Logger $logger = null;

    protected array $clients = [];

    protected array $servers = [];

    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->config->isEnabledDebug() && $this->logger = new Logger(
            path: $this->config->getRuntimePath(), days: $this->config->getMaxLogFiles(),
        );
    }

    /**
     * To send requests using the bank's API
     * @param string $bank
     * @return Client
     */
    public function getClient(string $bank = 'default'): Client
    {
        $bank = $this->getUsedBankName($bank);

        if (empty($this->clients[$bank])) {
            $bankProvider = sprintf('AntCool\EasyBank\Banks\%s', snake2camel($bank));

            if (!class_exists($bankProvider)) {
                throw new InvalidConfigException('Bank service provider do not exist.');
            }

            $this->clients[$bank] = new $bankProvider(
                http: data_get($this->config, "{$bank}.http", []),
                merchant: data_get($this->config, "{$bank}.default", []),
                logger: $this->logger,
            );
        }

        return $this->clients[$bank];
    }

    /**
     * Process requests sent by the bank's
     * @param string $bank
     * @return Server
     */
    public function getServer(string $bank = 'default'): Server
    {

    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    protected function getUsedBankName(string $bank): string
    {
        return $bank !== 'default' ?: $this->config->getDefualt();
    }
}