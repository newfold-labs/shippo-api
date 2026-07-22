<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Resources;

use Bluehost\ShippoAPI\Pagination\PaginatedResponse;

/**
 * Shippo Customs Declarations API resource.
 *
 * Creates and manages customs declaration objects required for international
 * shipments. Base endpoint: `/customs/declarations/`.
 *
 * @see https://docs.goshippo.com/api-reference/customs-declarations/
 */
final class CustomsDeclarations extends AbstractResource
{
    protected function resourcePath(): string
    {
        return 'customs/declarations';
    }

    /**
     * Create a new customs declaration.
     *
     * @param array{
     *     contents_type?: string,
     *     contents_explanation?: string,
     *     non_delivery_option?: string,
     *     certify?: bool,
     *     certify_signer?: string,
     *     items?: array<int, string|array<string, mixed>>,
     *     incoterm?: string,
     *     invoice?: string,
     *     license?: string,
     *     certificate?: string,
     *     notes?: string,
     *     eel_pfc?: string,
     *     aes_itn?: string,
     *     disclaimer?: string,
     *     metadata?: string,
     *     exporter_identification?: array<string, mixed>,
     *     importer_address?: array<string, mixed>|string,
     *     address_importer?: array<string, mixed>|string
     * } $declaration Customs declaration fields.
     *
     * @return array<string, mixed> Created customs declaration object.
     */
    public function create(array $declaration): array
    {
        return $this->post(null, null, $declaration);
    }

    /**
     * List all customs declaration objects.
     *
     * @param array{
     *     page?: int,
     *     results?: int,
     *     object_created_gt?: string,
     *     object_created_gte?: string,
     *     object_created_lt?: string,
     *     object_created_lte?: string
     * } $query Pagination and filtering parameters.
     *
     * @return PaginatedResponse<array<string, mixed>>
     */
    public function list(array $query = []): PaginatedResponse
    {
        return parent::list($query);
    }

    /**
     * Retrieve a customs declaration by object ID.
     *
     * @param string $objectId Shippo customs declaration object ID.
     *
     * @return array<string, mixed> Customs declaration object.
     *
     * @throws \InvalidArgumentException When the object ID is empty.
     */
    public function retrieve(string $objectId): array
    {
        $this->assertObjectId($objectId);

        return $this->get($objectId);
    }
}
