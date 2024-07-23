<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Traits;

use AntCool\EasyBank\Contracts\MerchantInterface;

trait UseMerchant
{
    protected MerchantInterface $merchant;

    public function getMerchant(): MerchantInterface
    {
        return $this->merchant;
    }
}