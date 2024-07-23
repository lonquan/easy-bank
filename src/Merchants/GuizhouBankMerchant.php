<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Merchants;

use AntCool\EasyBank\Support\Merchant;
use AntCool\EasyBank\Support\PrivateKey;
use AntCool\EasyBank\Support\PublicKey;

class GuizhouBankMerchant extends Merchant
{
    /**
     * Reconciliation key
     * @var PrivateKey
     */
    protected PrivateKey $reconciliationPrivateKey;

    /**
     * Reconciliation key
     * @var PublicKey
     */
    protected PublicKey $reconciliationPublicKey;

    public function __construct(array $merchant)
    {
        $this->setMerchantId($merchant['merchant_id'] ?? '');
        $this->setPrivateKey($merchant['merchant_private_key'] ?? '');
        $this->setPublicKey($merchant['merchant_public_key'] ?? '');

        // if (isset($merchant['reconciliation_private_key'])) {
        //     $this->reconciliationPrivateKey = new PrivateKey($merchant['reconciliation_private_key']);
        // }
        //
        // if (isset($merchant['reconciliation_public_key'])) {
        //     $this->reconciliationPublicKey = new PublicKey($merchant['reconciliation_public_key']);
        // }
    }
}