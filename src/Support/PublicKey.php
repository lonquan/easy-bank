<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Support;

use AntCool\EasyBank\Exceptions\InvalidConfigException;

class PublicKey
{
    protected string $key;

    protected \OpenSSLAsymmetricKey $openSSLAsymmetricKey;

    public function __construct(string $key)
    {
        $this->key = match (true) {
            file_exists($key) => file_get_contents($key) ?: '',
            !str_starts_with($key, '-----BEGIN') => $this->format($key),
            default => $key
        };
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getKeyResource(): \OpenSSLAsymmetricKey
    {
        if (!isset($this->openSSLAsymmetricKey)) {
            $resource = openssl_pkey_get_public($this->key);

            throw_if($resource === false, InvalidConfigException::class, 'The public key content is invalid.');

            $this->openSSLAsymmetricKey = $resource;
        }

        return $this->openSSLAsymmetricKey;
    }

    public function __toString(): string
    {
        return $this->getKey();
    }

    protected function format(string $key): string
    {
        return "-----BEGIN PUBLIC KEY-----\n{$key}\n-----END PUBLIC KEY-----";
    }
}