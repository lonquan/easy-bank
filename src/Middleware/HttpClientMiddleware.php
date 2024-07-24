<?php
declare(strict_types=1);

namespace EasyBank\Middleware;

use EasyBank\Support\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientMiddleware
{
    public function __construct(protected string $name, protected Logger $logger)
    {
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $this->request($request);
            $promise = $handler($request, $options);

            return $promise->then(
                function (ResponseInterface $response) {
                    $this->response($response);

                    return $response;
                },
            );
        };
    }

    protected function response(ResponseInterface $response): void
    {
        $body = $response->getBody();
        $body->rewind();

        $this->logger->info(
            sprintf('Response %s =>', $this->name),
            [
                'status' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => json_decode($body->getContents(), true)
            ]
        );
    }

    protected function request(RequestInterface $request): void
    {
        $body = $request->getBody();
        $body->rewind();

        $uri = $request->getUri();

        $this->logger->info(
            sprintf('Request %s =>', $this->name),
            [
                'method' => $request->getMethod(),
                'url' => sprintf(
                    '%s://%s:%s%s',
                    $uri->getScheme(),
                    $uri->getHost(),
                    $uri->getPort(),
                    $request->getRequestTarget(),
                ),
                'headers' => $request->getHeaders(),
                'body' => json_decode($body->getContents(), true)
            ],
        );
    }
}