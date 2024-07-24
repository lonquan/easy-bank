<?php
declare(strict_types=1);

namespace EasyBank\Support;

use EasyBank\Contracts\MerchantInterface;
use EasyBank\Exceptions\InvalidConfigException;

class Merchant implements MerchantInterface
{
    protected string|int $merchantId;

    protected PrivateKey $privateKey;

    protected PublicKey $publicKey;

    public function getMerchantId(): int|string
    {
        return $this->merchantId;
    }

    public function getPrivateKey(): PrivateKey
    {
        return $this->privateKey;
    }

    public function getPublicKey(): PublicKey
    {
        return $this->publicKey;
    }

    /**
     * @throws \Throwable
     */
    public function setMerchantId(int|string $mchId): void
    {
        throw_if(empty($mchId), InvalidConfigException::class, 'Merchant ID is a required parameter.');
        $this->merchantId = $mchId;
    }

    public function setPrivateKey(string $key): void
    {
        $this->privateKey = new PrivateKey($key);
    }

    public function setPublicKey(string $key): void
    {
        $this->publicKey = new PublicKey($key);
    }
}