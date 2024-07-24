<?php
declare(strict_types=1);

namespace EasyBank\Traits;

use EasyBank\Contracts\MerchantInterface;

trait UseMerchant
{
    protected MerchantInterface $merchant;

    public function getMerchant(): MerchantInterface
    {
        return $this->merchant;
    }
}