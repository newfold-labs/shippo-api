# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
## [1.0.1] - 2026-07-24
- Fix retrieve parcel carrier templates list
## [1.0.0] - 2026-07-17

### Added

- Initial release of the Shippo REST API PHP client (API version `2018-02-08`)
- `ShippoClient` facade with full resource coverage (22 API groups)
- Typed DTOs: `Address`, `Parcel`, `Shipment`, `Rate`, `Transaction`
- HTTP layer with Guzzle 7, `RateLimitMiddleware`, and exception mapping
- Authentication: `TokenAuthenticator`, `JwtAuthenticator`, `BearerAuthenticator`
- `EmbeddedAuth` resource for JWT generation (`/embedded/authz/`)
- `BatchPoller` for async batch status polling
- `WebhookVerifier` and `WebhookEvent` for inbound webhook security
- Reference constants: `ServiceLevels`, `ParcelTemplates` (PHP 8.0 compatible)
- Pagination via `PaginatedResponse`
- `expand` query parameter support on list/retrieve operations
- PHPUnit test suite with >= 80% code coverage target
- PHPStan level 8 static analysis
- PSR-12 coding standard (PHPCS)
- GitHub Actions CI matrix (PHP 8.0 – 8.3)

### Security

- HTTPS-only base URL enforcement
- API tokens never logged or included in exception messages
- Webhook HMAC and Shippo signature header verification
- IP allowlist helpers for Shippo webhook sources

[1.0.0]: https://github.com/blu/shippo-api/releases/tag/v1.0.0
