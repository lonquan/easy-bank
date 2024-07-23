<?php
declare(strict_types=1);

namespace AntCool\EasyBank\Banks;

use AntCool\EasyBank\Contracts\MerchantInterface;
use AntCool\EasyBank\Kernel\Client;
use AntCool\EasyBank\Merchants\GuizhouBankMerchant;

class GuizhouBank extends Client
{
    protected array $defaultHeaders = [
        'Content-Type' => 'application/json;charset=UTF-8',
    ];

    public function getName(): string
    {
        return 'GuizhouBank';
    }

    public function withMerchant(array|MerchantInterface $merchant): static
    {
        $this->merchant = is_array($merchant) ? new GuizhouBankMerchant($merchant) : $merchant;

        return $this;
    }

    public function handleSignature(array $options): array
    {
        if (!empty($options['json'])) {
            $options['json'] = $this->appendSign($options['json']);
        }

        return $options;
    }

    public function appendSign(array $body): array
    {
        ksort($body);
        $raw = http_build_query(array_filter($body));

        $signature = '';
        openssl_sign(
            data: $raw,
            signature: $signature,
            private_key: $this->merchant->getPrivateKey()->getKeyResource(),
            algorithm: OPENSSL_ALGO_SHA256,
        );

        $body['signature'] = urlencode(base64_encode($signature));

        return $body;
    }
}