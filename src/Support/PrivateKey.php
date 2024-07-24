<?php
declare(strict_types=1);

namespace EasyBank\Support;

use EasyBank\Exceptions\InvalidConfigException;

class PrivateKey
{
    protected string $key;

    protected \OpenSSLAsymmetricKey $openSSLAsymmetricKey;

    public function __construct(string $key, protected ?string $passphrase = null)
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
            $resource = openssl_pkey_get_private($this->key);

            throw_if($resource === false, InvalidConfigException::class, 'The private key content is invalid.');

            $this->openSSLAsymmetricKey = $resource;
        }

        return $this->openSSLAsymmetricKey;
    }

    public function getPassphrase(): ?string
    {
        return $this->passphrase;
    }

    public function __toString(): string
    {
        return $this->getKey();
    }

    protected function format(string $key): string
    {
        return "-----BEGIN PRIVATE KEY-----\n{$key}\n-----END PRIVATE KEY-----";
    }
}