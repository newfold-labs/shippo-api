<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\DTOs;

use Bluehost\ShippoAPI\DTOs\Concerns\ArraySerializable;

/**
 * Data transfer object representing a Shippo transaction (shipping label).
 */
final class Transaction
{
    use ArraySerializable;

    /**
     * Creates a new transaction DTO.
     *
     * @param string|null                        $rate                  Rate object ID to purchase.
     * @param string|null                        $labelFileType         Label file format (e.g. "PDF_4x6").
     * @param string|null                        $async                 Whether to create the label asynchronously.
     * @param array<string, mixed>|null          $metadata              Custom metadata key/value pairs.
     * @param string|null                        $objectId              Shippo object ID.
     * @param string|null                        $objectOwner           Owner account identifier.
     * @param string|null                        $objectCreated         ISO 8601 creation timestamp.
     * @param string|null                        $objectUpdated         ISO 8601 last update timestamp.
     * @param string|null                        $objectState           Object lifecycle state.
     * @param string|null                        $status                Transaction status (e.g. "SUCCESS").
     * @param string|null                        $trackingNumber        Carrier tracking number.
     * @param bool|null                          $test                  Whether this is a test-mode transaction.
     * @param string|null                        $trackingUrlProvider   Carrier tracking URL.
     * @param string|null                        $labelUrl              URL to download the shipping label.
     * @param string|null                        $commercialInvoiceUrl  URL to download the commercial invoice.
     * @param array<int, array<string, mixed>>|null $messages           Warning or error messages from the carrier.
     * @param string|null                        $qrCodeUrl             URL to the label QR code, if available.
     * @param array<string, mixed>|null          $parcel                Embedded parcel object data.
     * @param array<string, mixed>|null          $billing               Billing details for the transaction.
     */
    public function __construct(
        public ?string $rate = null,
        public ?string $labelFileType = null,
        public ?string $async = null,
        public ?array $metadata = null,
        public ?string $objectId = null,
        public ?string $objectOwner = null,
        public ?string $objectCreated = null,
        public ?string $objectUpdated = null,
        public ?string $objectState = null,
        public ?string $status = null,
        public ?string $trackingNumber = null,
        public ?bool $test = null,
        public ?string $trackingUrlProvider = null,
        public ?string $labelUrl = null,
        public ?string $commercialInvoiceUrl = null,
        public ?array $messages = null,
        public ?string $qrCodeUrl = null,
        public ?array $parcel = null,
        public ?array $billing = null
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    protected function serialize(): array
    {
        return [
            'rate' => $this->rate,
            'label_file_type' => $this->labelFileType,
            'async' => $this->async,
            'metadata' => $this->metadata,
            'object_id' => $this->objectId,
            'object_owner' => $this->objectOwner,
            'object_created' => $this->objectCreated,
            'object_updated' => $this->objectUpdated,
            'object_state' => $this->objectState,
            'status' => $this->status,
            'tracking_number' => $this->trackingNumber,
            'test' => $this->test,
            'tracking_url_provider' => $this->trackingUrlProvider,
            'label_url' => $this->labelUrl,
            'commercial_invoice_url' => $this->commercialInvoiceUrl,
            'messages' => $this->messages,
            'qr_code_url' => $this->qrCodeUrl,
            'parcel' => $this->parcel,
            'billing' => $this->billing,
        ];
    }
}
