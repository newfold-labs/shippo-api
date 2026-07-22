<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI;

use Bluehost\ShippoAPI\Auth\JwtAuthenticator;
use Bluehost\ShippoAPI\Auth\TokenAuthenticator;
use Bluehost\ShippoAPI\Config\ClientConfig;
use Bluehost\ShippoAPI\Contracts\AuthenticatorInterface;
use Bluehost\ShippoAPI\Contracts\HttpClientInterface;
use Bluehost\ShippoAPI\Http\GuzzleHttpClient;
use Bluehost\ShippoAPI\Http\RateLimitMiddleware;
use Bluehost\ShippoAPI\Polling\BatchPoller;
use Bluehost\ShippoAPI\Resources\AddressParser;
use Bluehost\ShippoAPI\Resources\Addresses;
use Bluehost\ShippoAPI\Resources\AddressValidation;
use Bluehost\ShippoAPI\Resources\Batches;
use Bluehost\ShippoAPI\Resources\CarrierAccounts;
use Bluehost\ShippoAPI\Resources\CarrierParcelTemplates;
use Bluehost\ShippoAPI\Resources\CustomsDeclarations;
use Bluehost\ShippoAPI\Resources\CustomsItems;
use Bluehost\ShippoAPI\Resources\EmbeddedAuth;
use Bluehost\ShippoAPI\Resources\Manifests;
use Bluehost\ShippoAPI\Resources\Orders;
use Bluehost\ShippoAPI\Resources\Parcels;
use Bluehost\ShippoAPI\Resources\Pickups;
use Bluehost\ShippoAPI\Resources\Rates;
use Bluehost\ShippoAPI\Resources\RatesAtCheckout;
use Bluehost\ShippoAPI\Resources\Refunds;
use Bluehost\ShippoAPI\Resources\ServiceGroups;
use Bluehost\ShippoAPI\Resources\Shipments;
use Bluehost\ShippoAPI\Resources\ShippoAccounts;
use Bluehost\ShippoAPI\Resources\Tracking;
use Bluehost\ShippoAPI\Resources\Transactions;
use Bluehost\ShippoAPI\Resources\UserParcelTemplates;
use Bluehost\ShippoAPI\Resources\Webhooks;
use Bluehost\ShippoAPI\Webhooks\WebhookVerifier;

/**
 * Facade for the Shippo REST API.
 *
 * Provides factory methods for client construction and lazy-loaded accessors
 * for each API resource group.
 */
final class ShippoClient
{
    /** @var HttpClientInterface */
    private $httpClient;

    /** @var Addresses|null */
    private $addresses;

    /** @var AddressParser|null */
    private $addressParser;

    /** @var AddressValidation|null */
    private $addressValidation;

    /** @var Batches|null */
    private $batches;

    /** @var CarrierAccounts|null */
    private $carrierAccounts;

    /** @var CarrierParcelTemplates|null */
    private $carrierParcelTemplates;

    /** @var CustomsDeclarations|null */
    private $customsDeclarations;

    /** @var CustomsItems|null */
    private $customsItems;

    /** @var Manifests|null */
    private $manifests;

    /** @var Orders|null */
    private $orders;

    /** @var Parcels|null */
    private $parcels;

    /** @var Pickups|null */
    private $pickups;

    /** @var Rates|null */
    private $rates;

    /** @var RatesAtCheckout|null */
    private $ratesAtCheckout;

    /** @var Refunds|null */
    private $refunds;

    /** @var ServiceGroups|null */
    private $serviceGroups;

    /** @var Shipments|null */
    private $shipments;

    /** @var ShippoAccounts|null */
    private $shippoAccounts;

    /** @var Tracking|null */
    private $tracking;

    /** @var Transactions|null */
    private $transactions;

    /** @var UserParcelTemplates|null */
    private $userParcelTemplates;

    /** @var Webhooks|null */
    private $webhooks;

    /** @var EmbeddedAuth|null */
    private $embeddedAuth;

    /** @var ClientConfig|null */
    private $config;

    /**
     * Creates a new Shippo client instance.
     *
     * @param HttpClientInterface $httpClient HTTP client used for API requests.
     * @param ClientConfig|null   $config     Optional client configuration.
     */
    public function __construct(HttpClientInterface $httpClient, ?ClientConfig $config = null)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * Creates a client authenticated with a Shippo API token.
     *
     * @param string             $apiToken Shippo API token (ShippoToken scheme).
     * @param ClientConfig|null  $config   Optional client configuration.
     *
     * @return self Configured Shippo client instance.
     */
    public static function create(string $apiToken, ?ClientConfig $config = null): self
    {
        if ($config === null) {
            $config = ClientConfig::create();
        }

        $transport = new GuzzleHttpClient($config, new TokenAuthenticator($apiToken));
        $httpClient = new RateLimitMiddleware($transport, $config->getMaxRetries());

        return new self($httpClient, $config);
    }

    /**
     * Creates a client authenticated with a JWT token.
     *
     * @param string             $jwtToken JWT bearer token for embedded auth flows.
     * @param ClientConfig|null  $config   Optional client configuration.
     *
     * @return self Configured Shippo client instance.
     */
    public static function createWithJwt(string $jwtToken, ?ClientConfig $config = null): self
    {
        if ($config === null) {
            $config = ClientConfig::create();
        }

        $transport = new GuzzleHttpClient($config, new JwtAuthenticator($jwtToken));
        $httpClient = new RateLimitMiddleware($transport, $config->getMaxRetries());

        return new self($httpClient, $config);
    }

    /**
     * Creates a client from an explicit configuration and authenticator.
     *
     * @param ClientConfig              $config                  Client configuration.
     * @param AuthenticatorInterface    $authenticator           Authentication strategy.
     * @param HttpClientInterface|null  $httpClient              Optional pre-built HTTP client.
     * @param bool                      $withRateLimitMiddleware Whether to wrap the client with rate-limit retry logic.
     *
     * @return self Configured Shippo client instance.
     */
    public static function fromConfig(
        ClientConfig $config,
        AuthenticatorInterface $authenticator,
        ?HttpClientInterface $httpClient = null,
        bool $withRateLimitMiddleware = true
    ): self {
        if ($httpClient === null) {
            $httpClient = new GuzzleHttpClient($config, $authenticator);
        }

        if ($withRateLimitMiddleware && ! $httpClient instanceof RateLimitMiddleware) {
            $httpClient = new RateLimitMiddleware($httpClient, $config->getMaxRetries());
        }

        return new self($httpClient, $config);
    }

    /**
     * Creates a batch poller for waiting on asynchronous batch operations.
     *
     * @param int|null $maxAttempts     Maximum poll attempts before timing out.
     * @param int|null $intervalSeconds Seconds to wait between poll attempts.
     *
     * @return BatchPoller Configured batch poller instance.
     */
    public function batchPoller(?int $maxAttempts = null, ?int $intervalSeconds = null): BatchPoller
    {
        $maxAttempts = $maxAttempts ?? 60;
        $intervalSeconds = $intervalSeconds ?? 2;

        return new BatchPoller($maxAttempts, $intervalSeconds);
    }

    /**
     * Creates a webhook signature verifier.
     *
     * @return WebhookVerifier Webhook verification helper.
     */
    public function webhookVerifier(): WebhookVerifier
    {
        return new WebhookVerifier();
    }

    /**
     * Returns the client configuration, if one was provided.
     *
     * @return ClientConfig|null Client configuration instance.
     */
    public function getConfig(): ?ClientConfig
    {
        return $this->config;
    }

    /**
     * Returns the Addresses API resource.
     *
     * @return Addresses Addresses resource instance.
     */
    public function addresses(): Addresses
    {
        if ($this->addresses === null) {
            $this->addresses = new Addresses($this->httpClient);
        }

        return $this->addresses;
    }

    /**
     * Returns the Address Parser API resource.
     *
     * @return AddressParser Address parser resource instance.
     */
    public function addressParser(): AddressParser
    {
        if ($this->addressParser === null) {
            $this->addressParser = new AddressParser($this->httpClient);
        }

        return $this->addressParser;
    }

    /**
     * Returns the Address Validation API resource.
     *
     * @return AddressValidation Address validation resource instance.
     */
    public function addressValidation(): AddressValidation
    {
        if ($this->addressValidation === null) {
            $this->addressValidation = new AddressValidation($this->httpClient);
        }

        return $this->addressValidation;
    }

    /**
     * Returns the Batches API resource.
     *
     * @return Batches Batches resource instance.
     */
    public function batches(): Batches
    {
        if ($this->batches === null) {
            $this->batches = new Batches($this->httpClient);
        }

        return $this->batches;
    }

    /**
     * Returns the Carrier Accounts API resource.
     *
     * @return CarrierAccounts Carrier accounts resource instance.
     */
    public function carrierAccounts(): CarrierAccounts
    {
        if ($this->carrierAccounts === null) {
            $this->carrierAccounts = new CarrierAccounts($this->httpClient);
        }

        return $this->carrierAccounts;
    }

    /**
     * Returns the Carrier Parcel Templates API resource.
     *
     * @return CarrierParcelTemplates Carrier parcel templates resource instance.
     */
    public function carrierParcelTemplates(): CarrierParcelTemplates
    {
        if ($this->carrierParcelTemplates === null) {
            $this->carrierParcelTemplates = new CarrierParcelTemplates($this->httpClient);
        }

        return $this->carrierParcelTemplates;
    }

    /**
     * Returns the Customs Declarations API resource.
     *
     * @return CustomsDeclarations Customs declarations resource instance.
     */
    public function customsDeclarations(): CustomsDeclarations
    {
        if ($this->customsDeclarations === null) {
            $this->customsDeclarations = new CustomsDeclarations($this->httpClient);
        }

        return $this->customsDeclarations;
    }

    /**
     * Returns the Customs Items API resource.
     *
     * @return CustomsItems Customs items resource instance.
     */
    public function customsItems(): CustomsItems
    {
        if ($this->customsItems === null) {
            $this->customsItems = new CustomsItems($this->httpClient);
        }

        return $this->customsItems;
    }

    /**
     * Returns the Manifests API resource.
     *
     * @return Manifests Manifests resource instance.
     */
    public function manifests(): Manifests
    {
        if ($this->manifests === null) {
            $this->manifests = new Manifests($this->httpClient);
        }

        return $this->manifests;
    }

    /**
     * Returns the Orders API resource.
     *
     * @return Orders Orders resource instance.
     */
    public function orders(): Orders
    {
        if ($this->orders === null) {
            $this->orders = new Orders($this->httpClient);
        }

        return $this->orders;
    }

    /**
     * Returns the Parcels API resource.
     *
     * @return Parcels Parcels resource instance.
     */
    public function parcels(): Parcels
    {
        if ($this->parcels === null) {
            $this->parcels = new Parcels($this->httpClient);
        }

        return $this->parcels;
    }

    /**
     * Returns the Pickups API resource.
     *
     * @return Pickups Pickups resource instance.
     */
    public function pickups(): Pickups
    {
        if ($this->pickups === null) {
            $this->pickups = new Pickups($this->httpClient);
        }

        return $this->pickups;
    }

    /**
     * Returns the Rates API resource.
     *
     * @return Rates Rates resource instance.
     */
    public function rates(): Rates
    {
        if ($this->rates === null) {
            $this->rates = new Rates($this->httpClient);
        }

        return $this->rates;
    }

    /**
     * Returns the Rates at Checkout API resource.
     *
     * @return RatesAtCheckout Rates at checkout resource instance.
     */
    public function ratesAtCheckout(): RatesAtCheckout
    {
        if ($this->ratesAtCheckout === null) {
            $this->ratesAtCheckout = new RatesAtCheckout($this->httpClient);
        }

        return $this->ratesAtCheckout;
    }

    /**
     * Returns the Refunds API resource.
     *
     * @return Refunds Refunds resource instance.
     */
    public function refunds(): Refunds
    {
        if ($this->refunds === null) {
            $this->refunds = new Refunds($this->httpClient);
        }

        return $this->refunds;
    }

    /**
     * Returns the Service Groups API resource.
     *
     * @return ServiceGroups Service groups resource instance.
     */
    public function serviceGroups(): ServiceGroups
    {
        if ($this->serviceGroups === null) {
            $this->serviceGroups = new ServiceGroups($this->httpClient);
        }

        return $this->serviceGroups;
    }

    /**
     * Returns the Shipments API resource.
     *
     * @return Shipments Shipments resource instance.
     */
    public function shipments(): Shipments
    {
        if ($this->shipments === null) {
            $this->shipments = new Shipments($this->httpClient);
        }

        return $this->shipments;
    }

    /**
     * Returns the Shippo Accounts API resource.
     *
     * @return ShippoAccounts Shippo accounts resource instance.
     */
    public function shippoAccounts(): ShippoAccounts
    {
        if ($this->shippoAccounts === null) {
            $this->shippoAccounts = new ShippoAccounts($this->httpClient);
        }

        return $this->shippoAccounts;
    }

    /**
     * Returns the Tracking API resource.
     *
     * @return Tracking Tracking resource instance.
     */
    public function tracking(): Tracking
    {
        if ($this->tracking === null) {
            $this->tracking = new Tracking($this->httpClient);
        }

        return $this->tracking;
    }

    /**
     * Returns the Transactions API resource.
     *
     * @return Transactions Transactions resource instance.
     */
    public function transactions(): Transactions
    {
        if ($this->transactions === null) {
            $this->transactions = new Transactions($this->httpClient);
        }

        return $this->transactions;
    }

    /**
     * Returns the User Parcel Templates API resource.
     *
     * @return UserParcelTemplates User parcel templates resource instance.
     */
    public function userParcelTemplates(): UserParcelTemplates
    {
        if ($this->userParcelTemplates === null) {
            $this->userParcelTemplates = new UserParcelTemplates($this->httpClient);
        }

        return $this->userParcelTemplates;
    }

    /**
     * Returns the Webhooks API resource.
     *
     * @return Webhooks Webhooks resource instance.
     */
    public function webhooks(): Webhooks
    {
        if ($this->webhooks === null) {
            $this->webhooks = new Webhooks($this->httpClient);
        }

        return $this->webhooks;
    }

    /**
     * Returns the Embedded Auth API resource.
     *
     * @return EmbeddedAuth Embedded auth resource instance.
     */
    public function embeddedAuth(): EmbeddedAuth
    {
        if ($this->embeddedAuth === null) {
            $this->embeddedAuth = new EmbeddedAuth($this->httpClient);
        }

        return $this->embeddedAuth;
    }
}
