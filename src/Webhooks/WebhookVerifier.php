<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Webhooks;

/**
 * Verifies authenticity of incoming Shippo webhook requests.
 */
final class WebhookVerifier
{
    /** @var array<int, string> Known Shippo webhook source IPs for the US region. */
    public const US_IP_ALLOWLIST = [
        '52.4.41.98',
        '52.23.121.194',
        '52.44.110.80',
        '54.81.253.187',
        '54.81.255.221',
    ];

    /** @var array<int, string> Known Shippo webhook source IPs for the EU region. */
    public const EU_IP_ALLOWLIST = [
        '34.248.247.69',
        '34.253.119.130',
        '52.214.174.64',
        '54.72.179.250',
    ];

    /**
     * Verifies an HMAC-SHA256 signature against the raw request payload.
     *
     * @param string $payload   Raw request body.
     * @param string $signature Signature value to compare.
     * @param string $secret    Shared webhook secret.
     *
     * @return bool True when the signature is valid.
     */
    public function verifyHmac(string $payload, string $signature, string $secret): bool
    {
        if ($signature === '' || $secret === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * Verifies a Shippo webhook signature header in the format t=TIMESTAMP,v1=SIGNATURE.
     *
     * @param string $payload         Raw request body.
     * @param string $signatureHeader Value of the Shippo signature header.
     * @param string $secret            Shared webhook secret.
     *
     * @return bool True when the signature is valid.
     *
     * @see https://docs.goshippo.com/tracking/webhook-security
     */
    public function verifyShippoSignatureHeader(string $payload, string $signatureHeader, string $secret): bool
    {
        if ($payload === '' || $signatureHeader === '' || $secret === '') {
            return false;
        }

        $parts = $this->parseSignatureHeader($signatureHeader);
        if ($parts === null) {
            return false;
        }

        $signedPayload = $parts['timestamp'] . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, $parts['signature']);
    }

    /**
     * Verifies that a remote IP address is in the allowed list.
     *
     * @param string              $remoteIp   Client IP address.
     * @param array<int, string>  $allowedIps Allowed IP addresses.
     *
     * @return bool True when the IP is allowed.
     */
    public function verifyIp(string $remoteIp, array $allowedIps): bool
    {
        if ($remoteIp === '') {
            return false;
        }

        return in_array($remoteIp, $allowedIps, true);
    }

    /**
     * Verifies a URL token using a timing-safe comparison.
     *
     * @param string $providedToken Token received in the request.
     * @param string $expectedToken Expected token value.
     *
     * @return bool True when the tokens match.
     */
    public function verifyUrlToken(string $providedToken, string $expectedToken): bool
    {
        if ($providedToken === '' || $expectedToken === '') {
            return false;
        }

        return hash_equals($expectedToken, $providedToken);
    }

    /**
     * @return array{timestamp: string, signature: string}|null
     */
    private function parseSignatureHeader(string $header): ?array
    {
        $timestamp = null;
        $signature = null;

        foreach (explode(',', $header) as $part) {
            $segments = explode('=', trim($part), 2);
            if (count($segments) !== 2) {
                continue;
            }

            if ($segments[0] === 't') {
                $timestamp = $segments[1];
            }

            if ($segments[0] === 'v1') {
                $signature = $segments[1];
            }
        }

        if ($timestamp === null || $signature === null) {
            return null;
        }

        return [
            'timestamp' => $timestamp,
            'signature' => $signature,
        ];
    }
}
