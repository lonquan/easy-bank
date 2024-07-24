<?php
declare(strict_types=1);

namespace EasyBank\Contracts;

interface BankInterface
{
    /**
     * Get bank name
     * @return string
     */
    public function getName(): string;

    /**
     * Set current use merchant
     * @param array|MerchantInterface $merchant
     * @return $this
     */
    public function withMerchant(array|MerchantInterface $merchant): static;

    /**
     * Get merchant
     * @return MerchantInterface
     */
    public function getMerchant(): MerchantInterface;
}