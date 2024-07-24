<?php
declare(strict_types=1);

namespace EasyBank\Traits;

use EasyBank\EasyBank;
use GuzzleHttp\Client as Http;

trait UseHttpClient
{
    protected Http $http;

    protected bool $throwException = true;

    protected array $defaultHeaders = [];

    public function setThrowException(bool $throw): void
    {
        $this->throwException = $throw;
    }

    public function isThrowException(): bool
    {
        return $this->throwException;
    }

    public function getJson(string $uri, array $query = []): mixed
    {
        return $this->request(method: 'GET', uri: $uri, options: ['query' => $query]);
    }

    /**
     * @throws GuzzleException
     * @throws ResponseInvalidException
     */
    public function postJson(string $uri, array $data = [], array $query = null): mixed
    {
        return $this->request(method: 'POST', uri: $uri, options: [
            'query' => $query,
            'json' => $data,
        ]);
    }

    public function uploadFile(string $uri, File $file, array $data = [], array $query = []): array
    {
        return $this->request(method: 'POST', uri: $uri, options: [
            'query' => $query,
            'multipart' => $this->buildForm($file, $data),
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws ResponseInvalidException
     */
    public function request(string $method, string $uri, $options = []): mixed
    {
        $options['headers'] = array_merge(
            $this->defaultHeaders,
            $options['headers'] ?? [],
            ['User-Agent' => 'EasyBank/' . EasyBank::SDK_VERSION],
        );

        if (method_exists($this, 'handleSignature')) {
            $options = $this->handleSignature($options);
        }

        $response = $this->http->request($method, $uri, $options);
        $body = $response->getBody();
        $body->rewind();

        return json_decode($body->getContents(), true);
    }

    protected function buildForm(File $file, array $data): array
    {
        $form = [];

        foreach ($data as $key => $value) {
            $form[] = ['name' => $key, 'contents' => $value];
        }

        $form[] = ['name' => 'file', 'contents' => $file->getContents()];

        return $form;
    }
}