# Shippo API PHP Client

Modern, type-safe PHP client for the [Shippo REST API](https://docs.goshippo.com/api-reference/overview) (version `2018-02-08`).

## Requirements

- PHP 8.0+
- Composer
- ext-json

## Installation

```bash
composer require newfold-labs/shippo-api
```

## Quickstart

Create a shipment and purchase a label:

```php
<?php

use Bluehost\ShippoAPI\DTOs\Address;
use Bluehost\ShippoAPI\DTOs\Parcel;
use Bluehost\ShippoAPI\DTOs\Shipment;
use Bluehost\ShippoAPI\DTOs\Transaction;
use Bluehost\ShippoAPI\ShippoClient;

$client = ShippoClient::create(getenv('SHIPPO_API_TOKEN'));

$from = new Address(
    name: 'Sender',
    street1: '215 Clayton St',
    city: 'San Francisco',
    state: 'CA',
    zip: '94117',
    country: 'US'
);

$to = new Address(
    name: 'Recipient',
    street1: '965 Mission St',
    city: 'San Francisco',
    state: 'CA',
    zip: '94103',
    country: 'US'
);

$shipment = $client->shipments()->create(new Shipment(
    addressFrom: $from,
    addressTo: $to,
    parcels: [
        new Parcel(
            length: '5',
            width: '5',
            height: '5',
            distanceUnit: 'in',
            weight: '2',
            massUnit: 'lb'
        ),
    ],
    async: false
));

$rateId = $shipment['rates'][0]['object_id'];
$transaction = $client->transactions()->create(new Transaction(rate: $rateId));

echo $transaction['label_url'];
```

Use a **test API key** (`shippo_test_*`) during development — no charges apply. See [Shippo testing guide](https://docs.goshippo.com/guides/testing).

## Authentication

| Method | Usage |
|---|---|
| API token (default) | `ShippoClient::create($apiToken)` |
| JWT | `ShippoClient::createWithJwt($jwtToken)` |
| Custom | `ShippoClient::fromConfig($config, $authenticator)` |

Generate JWTs for embedded / platform integrations:

```php
$auth = $client->embeddedAuth()->authorizeForAccount('managed_account_id', [
    'scope' => 'embedded:carriers',
]);

$embeddedClient = ShippoClient::createWithJwt($auth['token']);
```

## Resources

| Client method | API group | Operations |
|---|---|---|
| `addresses()` | Addresses | create, list, retrieve, validate, update, delete |
| `addressParser()` | Address parser | parse |
| `addressValidation()` | Address validation v2 | validate |
| `batches()` | Batches | create, retrieve, addShipments, removeShipments, purchase |
| `carrierAccounts()` | Carrier accounts | create, list, retrieve, update, registerNew, registerOauth, getRegistrationStatus |
| `carrierParcelTemplates()` | Carrier parcel templates | list, retrieve |
| `customsDeclarations()` | Customs declarations | create, list, retrieve |
| `customsItems()` | Customs items | create, list, retrieve |
| `manifests()` | Manifests | create, list, retrieve |
| `orders()` | Orders | create, list, retrieve |
| `parcels()` | Parcels | create, list, retrieve |
| `pickups()` | Pickups | create |
| `rates()` | Rates | retrieve, retrieveForShipment, retrieveForShipmentInCurrency |
| `ratesAtCheckout()` | Live rates | generate, show/update/clear default parcel template |
| `refunds()` | Refunds | create, list, retrieve |
| `serviceGroups()` | Service groups | create, list, update, delete |
| `shipments()` | Shipments | create, list, retrieve (+ expand) |
| `shippoAccounts()` | Platform accounts | create, list, retrieve, update |
| `tracking()` | Tracking | retrieveStatus, registerWebhook |
| `transactions()` | Labels / transactions | create, list, retrieve |
| `userParcelTemplates()` | User parcel templates | create, list, retrieve, update, delete |
| `webhooks()` | Webhooks | create, list, retrieve, update, delete |
| `embeddedAuth()` | JWT authorization | authorize, authorizeForAccount |

## Examples by use case

### Batch label purchase

```php
$batch = $client->batches()->create([
    'default_carrier_account' => 'your_carrier_account_object_id',
    'batch_shipments' => [/* ... */],
]);

$poller = $client->batchPoller();
$validated = $poller->waitUntilValidated($client->batches(), $batch['object_id']);

if ($validated['batch_status'] === 'VALID') {
    $client->batches()->purchase($batch['object_id']);
    $purchased = $poller->waitUntilPurchased($client->batches(), $batch['object_id']);
}
```

### Track a shipment

```php
$status = $client->tracking()->retrieveStatus('usps', '9400111899223197428490');
echo $status['tracking_status']['status'];
```

### Verify inbound webhooks

```php
$verifier = $client->webhookVerifier();
$rawBody = file_get_contents('php://input');

if (! $verifier->verifyShippoSignatureHeader(
    $rawBody,
    $_SERVER['HTTP_SHIPPO_AUTH_SIGNATURE'] ?? '',
    getenv('SHIPPO_WEBHOOK_SECRET')
)) {
    http_response_code(401);
    exit;
}

$event = \Bluehost\ShippoAPI\Webhooks\WebhookEvent::fromArray(json_decode($rawBody, true));
```

### Reference constants

```php
use Bluehost\ShippoAPI\Reference\ParcelTemplates;
use Bluehost\ShippoAPI\Reference\ServiceLevels;

ParcelTemplates::isValid(ParcelTemplates::USPS_SMALL_FLAT_RATE_BOX);
ServiceLevels::isValid(ServiceLevels::USPS_PRIORITY);
```

### Expand nested objects

```php
$shipment = $client->shipments()->retrieve('shipment_id', [], ['rates', 'address_from']);
```

## Error handling

```php
use Bluehost\ShippoAPI\Exceptions\AuthenticationException;
use Bluehost\ShippoAPI\Exceptions\RateLimitException;
use Bluehost\ShippoAPI\Exceptions\ValidationException;

try {
    $client->addresses()->create($address);
} catch (AuthenticationException $e) {
    // Invalid or missing API token (401)
} catch (ValidationException $e) {
    // Invalid request data (400/422) — $e->getResponseBody()
} catch (RateLimitException $e) {
    // Rate limited (429) — $e->getRetryAfter()
}
```

## Configuration

```php
use Bluehost\ShippoAPI\Config\ClientConfig;

$config = ClientConfig::create()
    ->withDebug(false)
    ->withBaseUrl('https://api.goshippo.com');

$client = ShippoClient::create(getenv('SHIPPO_API_TOKEN'), $config);
```

| Setting | Default |
|---|---|
| Base URL | `https://api.goshippo.com` |
| API version header | `2018-02-08` |
| Connect timeout | 10s |
| Request timeout | 30s |
| Max retries (429/5xx) | 3 |

## Development

```bash
composer install
composer test              # Run unit tests
composer test:coverage     # Tests + coverage report (requires pcov or xdebug)
composer analyse           # PHPStan level 8
composer lint              # PSR-12 via PHPCS
composer ci                # lint + analyse + coverage
```

### Integration tests (optional)

```bash
SHIPPO_API_TOKEN=shippo_test_your_key vendor/bin/phpunit --group integration
```

## Project structure

```
src/
├── ShippoClient.php       # Entry point
├── Auth/                  # Token, JWT, Bearer authenticators
├── Config/                # ClientConfig
├── Contracts/             # Interfaces
├── DTOs/                  # Request/response objects
├── Exceptions/            # Typed API exceptions
├── Http/                  # Guzzle client, middleware, parsers
├── Pagination/            # PaginatedResponse
├── Polling/               # BatchPoller
├── Reference/             # Service level & parcel template constants
├── Resources/             # API resource classes
└── Webhooks/              # WebhookVerifier, WebhookEvent
```

## License

MIT — see [CHANGELOG.md](CHANGELOG.md) for release history.
