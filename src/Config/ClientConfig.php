<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Config;

use InvalidArgumentException;

/**
 * Immutable configuration for the Shippo HTTP client.
 *
 * Holds base URL, API version, timeouts, retry limits, and debug settings.
 */
final class ClientConfig
{
    /** @var string Default Shippo API base URL. */
    public const DEFAULT_BASE_URL = 'https://api.goshippo.com';

    /** @var string Default Shippo API version header value. */
    public const DEFAULT_API_VERSION = '2018-02-08';

    /** @var int Default connection timeout in seconds. */
    public const DEFAULT_CONNECT_TIMEOUT = 10;

    /** @var int Default request timeout in seconds. */
    public const DEFAULT_TIMEOUT = 30;

    /** @var int Default maximum number of rate-limit retries. */
    public const DEFAULT_MAX_RETRIES = 3;

    /**
     * Creates a new client configuration instance.
     *
     * @param string $baseUrl         Base URL for the Shippo API (must use HTTPS).
     * @param string $apiVersion      Value for the Shippo-API-Version header.
     * @param int    $connectTimeout  Connection timeout in seconds.
     * @param int    $timeout         Request timeout in seconds.
     * @param int    $maxRetries      Maximum number of rate-limit retries.
     * @param bool   $debug           Whether to send debug headers with requests.
     *
     * @throws \InvalidArgumentException When the base URL does not use HTTPS.
     */
    public function __construct(
        private string $baseUrl = self::DEFAULT_BASE_URL,
        private string $apiVersion = self::DEFAULT_API_VERSION,
        private int $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT,
        private int $timeout = self::DEFAULT_TIMEOUT,
        private int $maxRetries = self::DEFAULT_MAX_RETRIES,
        private bool $debug = false
    ) {
        $this->assertHttpsUrl($baseUrl);
    }

    /**
     * Creates a client configuration with default values.
     *
     * @return self New configuration instance.
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Returns the configured base URL without a trailing slash.
     *
     * @return string Base URL for API requests.
     */
    public function getBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }

    /**
     * Returns the Shippo API version sent in request headers.
     *
     * @return string API version string.
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Returns the connection timeout in seconds.
     *
     * @return int Connection timeout.
     */
    public function getConnectTimeout(): int
    {
        return $this->connectTimeout;
    }

    /**
     * Returns the request timeout in seconds.
     *
     * @return int Request timeout.
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Returns the maximum number of rate-limit retries.
     *
     * @return int Maximum retry count.
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * Returns whether debug mode is enabled.
     *
     * @return bool True when debug headers should be sent.
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Returns a copy of this configuration with a different base URL.
     *
     * @param string $baseUrl New base URL (must use HTTPS).
     *
     * @return self New configuration instance.
     *
     * @throws \InvalidArgumentException When the base URL does not use HTTPS.
     */
    public function withBaseUrl(string $baseUrl): self
    {
        return new self(
            $baseUrl,
            $this->apiVersion,
            $this->connectTimeout,
            $this->timeout,
            $this->maxRetries,
            $this->debug
        );
    }

    /**
     * Returns a copy of this configuration with debug mode toggled.
     *
     * @param bool $debug Whether to enable debug mode.
     *
     * @return self New configuration instance.
     */
    public function withDebug(bool $debug): self
    {
        return new self(
            $this->baseUrl,
            $this->apiVersion,
            $this->connectTimeout,
            $this->timeout,
            $this->maxRetries,
            $debug
        );
    }

    private function assertHttpsUrl(string $url): void
    {
        if (! str_starts_with($url, 'https://')) {
            throw new InvalidArgumentException('Shippo API base URL must use HTTPS.');
        }
    }
}
