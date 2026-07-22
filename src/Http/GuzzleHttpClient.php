<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Bluehost\ShippoAPI\Config\ClientConfig;
use Bluehost\ShippoAPI\Contracts\AuthenticatorInterface;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Exceptions\ShippoException;

/**
 * Guzzle-based HTTP client for the Shippo REST API.
 */
final class GuzzleHttpClient implements HttpClientInterface
{
    /** @var Client */
    private $client;

    /** @var RequestBuilder */
    private $requestBuilder;

    /** @var ResponseParser */
    private $responseParser;

    /** @var ClientConfig */
    private $config;

    /** @var AuthenticatorInterface */
    private $authenticator;

    /**
     * Creates a new Guzzle HTTP client.
     *
     * @param ClientConfig            $config        Client configuration.
     * @param AuthenticatorInterface  $authenticator Authentication strategy.
     * @param Client|null             $client        Optional pre-configured Guzzle client.
     */
    public function __construct(
        ClientConfig $config,
        AuthenticatorInterface $authenticator,
        ?Client $client = null
    ) {
        $this->config = $config;
        $this->authenticator = $authenticator;
        $this->requestBuilder = new RequestBuilder();
        $this->responseParser = new ResponseParser();
        $this->client = $client ?? new Client([
            'base_uri' => $this->config->getBaseUrl(),
            'connect_timeout' => $this->config->getConnectTimeout(),
            'timeout' => $this->config->getTimeout(),
            'http_errors' => false,
            'verify' => true,
        ]);
    }

    /**
     * Sends an HTTP request to the Shippo API and returns the decoded response.
     *
     * @param string                    $method  HTTP method (e.g. GET, POST).
     * @param string                    $path    API path relative to the base URL.
     * @param array<string, mixed>      $query   Query string parameters.
     * @param array<string, mixed>|null $body    JSON request body, or null for no body.
     * @param array<string, string>     $headers Additional HTTP headers.
     *
     * @return array<string, mixed> Decoded JSON response body.
     *
     * @throws ShippoException When the HTTP transport fails or the response cannot be parsed.
     */
    public function request(
        string $method,
        string $path,
        array $query = [],
        ?array $body = null,
        array $headers = []
    ): array {
        try {
            $response = $this->sendRequest($method, $path, $query, $body, $headers);
        } catch (GuzzleException $exception) {
            throw new ShippoException('HTTP request to Shippo API failed.', 0, $exception);
        }

        $statusCode = $response->getStatusCode();
        $responseBody = (string) $response->getBody();
        $retryAfter = $response->getHeaderLine('Retry-After');

        return $this->responseParser->parse($responseBody, $statusCode, $retryAfter !== '' ? $retryAfter : null);
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed>|null $body
     * @param array<string, string> $headers
     */
    private function sendRequest(
        string $method,
        string $path,
        array $query,
        ?array $body,
        array $headers
    ): ResponseInterface {
        $options = [
            'headers' => array_merge(
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Shippo-API-Version' => $this->config->getApiVersion(),
                ],
                $this->authenticator->getHeaders(),
                $headers
            ),
            'query' => $this->requestBuilder->buildQuery($query),
        ];

        if ($body !== null) {
            $options['json'] = $body;
        }

        if ($this->config->isDebug()) {
            $options['headers']['X-Shippo-Debug'] = '1';
        }

        return $this->client->request(strtoupper($method), $path, $options);
    }
}
