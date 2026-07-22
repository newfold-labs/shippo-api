<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Webhooks;

/**
 * Parsed representation of a Shippo webhook event payload.
 */
final class WebhookEvent
{
    /** @var string */
    private $event;

    /** @var bool */
    private $test;

    /** @var array<string, mixed> */
    private $data;

    /**
     * Creates a webhook event from a decoded API payload array.
     *
     * @param array<string, mixed> $payload Raw webhook JSON payload.
     *
     * @return self Parsed webhook event.
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            isset($payload['event']) && is_string($payload['event']) ? $payload['event'] : '',
            isset($payload['test']) ? (bool) $payload['test'] : false,
            isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : []
        );
    }

    /**
     * Creates a new webhook event instance.
     *
     * @param string               $event Event type name (e.g. "track_updated").
     * @param bool                 $test  Whether this is a test-mode event.
     * @param array<string, mixed> $data  Event-specific payload data.
     */
    public function __construct(string $event, bool $test, array $data)
    {
        $this->event = $event;
        $this->test = $test;
        $this->data = $data;
    }

    /**
     * Returns the webhook event type name.
     *
     * @return string Event type.
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * Returns whether this event was generated in test mode.
     *
     * @return bool True for test-mode events.
     */
    public function isTest(): bool
    {
        return $this->test;
    }

    /**
     * Returns the event-specific payload data.
     *
     * @return array<string, mixed> Event data.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
