<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Http;

use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Exceptions\RateLimitException;
use Bluehost\ShippoAPI\Exceptions\ShippoException;

/**
 * Decorator that retries requests when rate limited (HTTP 429) or on server errors.
 */
final class RateLimitMiddleware implements HttpClientInterface
{
    /** @var HttpClientInterface */
    private $innerClient;

    /** @var int */
    private $maxRetries;

    /**
     * Creates a new rate-limit retry middleware.
     *
     * @param HttpClientInterface $innerClient HTTP client to wrap.
     * @param int                 $maxRetries  Maximum number of retries after the initial attempt.
     */
    public function __construct(HttpClientInterface $innerClient, int $maxRetries = 3)
    {
        $this->innerClient = $innerClient;
        $this->maxRetries = max(0, $maxRetries);
    }

    /**
     * Sends an HTTP request, retrying on rate limits and server errors.
     *
     * @param string                    $method  HTTP method (e.g. GET, POST).
     * @param string                    $path    API path relative to the base URL.
     * @param array<string, mixed>      $query   Query string parameters.
     * @param array<string, mixed>|null $body    JSON request body, or null for no body.
     * @param array<string, string>     $headers Additional HTTP headers.
     *
     * @return array<string, mixed> Decoded JSON response body.
     *
     * @throws RateLimitException When rate limit retries are exhausted.
     * @throws ShippoException    When a non-retryable error occurs.
     */
    public function request(
        string $method,
        string $path,
        array $query = [],
        ?array $body = null,
        array $headers = []
    ): array {
        $attempt = 0;
        $maxAttempts = $this->maxRetries + 1;

        while (true) {
            try {
                return $this->innerClient->request($method, $path, $query, $body, $headers);
            } catch (RateLimitException $exception) {
                if ($attempt >= $maxAttempts - 1) {
                    throw $exception;
                }

                $this->sleepBeforeRetry($exception->getRetryAfter(), $attempt);
                ++$attempt;
            } catch (ShippoException $exception) {
                if ($exception->getCode() >= 500 && $attempt < $maxAttempts - 1) {
                    $this->sleepBeforeRetry(null, $attempt);
                    ++$attempt;
                    continue;
                }

                throw $exception;
            }
        }
    }

    private function sleepBeforeRetry(?int $retryAfter, int $attempt): void
    {
        $seconds = $retryAfter !== null ? max(1, $retryAfter) : (2 ** $attempt);
        sleep($seconds);
    }
}
