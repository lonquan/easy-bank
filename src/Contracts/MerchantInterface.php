<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Contracts;

use AntCool\EasyBank\Support\PrivateKey;
use AntCool\EasyBank\Support\PublicKey;

interface MerchantInterface
{
    /**
     * Get merchant id
     * @return int|string
     */
    public function getMerchantId(): int|string;

    /**
     * Get private key
     * @return PrivateKey
     */
    public function getPrivateKey(): PrivateKey;

    /**
     * Get public key
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey;

    /**
     * Set merchant id
     * @param string|int $mchId
     * @return void
     */
    public function setMerchantId(string|int $mchId): void;

    /**
     * set private key
     * @param string $key
     * @return void
     */
    public function setPrivateKey(string $key): void;

    /**
     * set public key
     * @param string $key
     * @return void
     */
    public function setPublicKey(string $key): void;
}