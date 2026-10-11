# ResellerClub PHP SDK

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A modern, robust PHP SDK for interacting with the **ResellerClub** and **LogicBoxes** HTTP APIs, aligned with official API documentation ([ResellerClub KB 744](https://resellerclub.webpropanel.com/kb/answer/744)).

---

## Features

- **Domain Management:** Availability check, registrations, transfers, renewals, locks, privacy protection, child nameservers, DNSSEC.
- **Web Hosting:** Single Domain & Multi Domain (Linux cPanel / Windows Plesk), Reseller Hosting, and Cloud Hosting provisioning.
- **Virtual Private Servers (VPS):** Provisioning, reboot, start, stop, and order management for KVM instances.
- **Business & Enterprise Email:** Add email domains, create/delete accounts, password updates, and forwarders.
- **Managed DNS:** Add, search, and delete DNS records (A, AAAA, CNAME, MX, TXT, SRV, NS).
- **Products & Pricing:** Catalog mapping, cost pricing, reseller pricing, customer pricing, and plan details.
- **Customers & Contacts:** Registrant contacts, customer account creation, modifications, and credential resets.
- **Typed Exceptions:** Clean error handling (`AuthenticationException`, `ValidationException`, `ResourceNotFoundException`).
- **PHP 8.1+ / 8.4 / 8.5 Ready:** Strict types, clean constructor properties, and PSR-7 compliance.

---

## Installation

```bash
composer require insigndigital/resellerclub-php-api
```

---

## Requirements & API Setup

1. **IP Whitelisting:**  
   Before executing requests in production or test environments, you **must whitelist your server's outbound IP address** in your ResellerClub Control Panel under:  
   `Settings > API > IP Whitelist`.
2. **Credentials:**  
   Obtain your `auth-userid` and `api-key` in `Settings > API`.

---

## Quick Start

```php
<?php

use ResellerClub\ResellerClub;
use ResellerClub\Exceptions\AuthenticationException;
use ResellerClub\Exceptions\ResellerClubApiException;

require 'vendor/autoload.php';

$userId = '123456';
$apiKey = 'your_api_key_here';
$testMode = false; // set true for test.httpapi.com

$rc = new ResellerClub($userId, $apiKey, $testMode);

try {
    // 1. Check domain availability
    $result = $rc->domains()->available(['mysite', 'example'], ['com', 'com.br', 'net']);
    print_r($result);

    // 2. Provision Single Domain Linux Hosting
    $order = $rc->hosting()->addLinuxSingle(
        domain: 'mysite.com',
        customerId: 987654,
        months: 12,
        plan: 'singledomainhostinglinuxus_plan1',
        location: 'us'
    );
    echo "Hosting Order ID: " . $order['entityid'] . PHP_EOL;

} catch (AuthenticationException $e) {
    echo "Authentication failed or IP not whitelisted: " . $e->getMessage();
} catch (ResellerClubApiException $e) {
    echo "API Error [HTTP {$e->getHttpStatusCode()}]: " . $e->getMessage();
    print_r($e->getResponseData());
}
```

---

## API Modules

### 1. Domains (`$rc->domains()`)
```php
// Check availability
$rc->domains()->available(['google', 'mydomain'], ['com', 'net']);

// Register domain
$rc->domains()->register(
    domain: 'mydomain.com',
    years: 1,
    ns: ['ns1.example.com', 'ns2.example.com'],
    customerId: $customerId,
    regContactId: $contactId,
    adminContactId: $contactId,
    techContactId: $contactId,
    billingContactId: $contactId,
    invoiceOption: 'NoInvoice'
);

// Renew domain
$rc->domains()->renew(orderId: $orderId, years: 1, exp: $curExpTimestamp);

// Name server modifications
$rc->domains()->modifyNameServers(orderId: $orderId, ns: ['ns1.new.com', 'ns2.new.com']);
```

### 2. Web Hosting (`$rc->hosting()`)
```php
// Single Domain Linux Hosting (cPanel)
$rc->hosting()->addLinuxSingle('example.com', $customerId, 12, 'plan_id', 'us');

// Multi Domain Linux Hosting
$rc->hosting()->addLinuxMulti('example.com', $customerId, 12, 'plan_id', 'us');

// Windows Single Domain Hosting (Plesk)
$rc->hosting()->addWindowsSingle('example.com', $customerId, 12, 'plan_id', 'us');

// Details and lifecycle
$rc->hosting()->detailsLinuxSingle($orderId);
$rc->hosting()->renew('singledomainhosting/linux', $orderId, 12);
$rc->hosting()->delete('singledomainhosting/linux', $orderId);
```

### 3. VPS Servers (`$rc->vps()`)
```php
// Provision KVM VPS
$rc->vps()->add(domain: 'vps.example.com', customerId: $customerId, months: 1, plan: 'vps_plan1', location: 'us');

// Power management
$rc->vps()->reboot($orderId);
$rc->vps()->start($orderId);
$rc->vps()->stop($orderId);
```

### 4. Enterprise Email (`$rc->mail()`)
```php
// Add Email Service for a domain
$rc->mail()->addDomain(domain: 'example.com', customerId: $customerId, months: 12, numberOfAccounts: 5);

// Add an email mailbox account
$rc->mail()->addUser(
    orderId: $orderId,
    email: 'contact@example.com',
    password: 'SecurePassword123!',
    firstName: 'John',
    lastName: 'Doe'
);

// Change mailbox password
$rc->mail()->changePassword($orderId, 'contact@example.com', 'NewPassword456!');

// Add forwarder
$rc->mail()->addForward($orderId, 'contact@example.com', ['forward@otherdomain.com']);
```

### 5. Managed DNS (`$rc->dns()`)
```php
// Add A record
$rc->dns()->addA('example.com', '192.0.2.1', 'www');

// Add MX record
$rc->dns()->addMx('example.com', 'mail.example.com', priority: 10);

// Add TXT record (SPF, DKIM, etc.)
$rc->dns()->addTxt('example.com', 'v=spf1 include:_spf.google.com ~all');

// Search records
$rc->dns()->search('example.com');

// Delete record
$rc->dns()->delete('example.com', type: 'A', currentValue: '192.0.2.1', host: 'www');
```

### 6. Products & Pricing (`$rc->products()`)
```php
// Reseller cost prices
$costPrices = $rc->products()->resellerCostPrice();

// Customer selling prices
$customerPrices = $rc->products()->customerPrice();

// Category and product keys mapping
$mapping = $rc->products()->categoryKeysMapping();
```

---

## Error Handling

All API calls throw typed domain exceptions extending `ResellerClubApiException`:

```php
use ResellerClub\Exceptions\AuthenticationException;
use ResellerClub\Exceptions\ValidationException;
use ResellerClub\Exceptions\ResourceNotFoundException;
use ResellerClub\Exceptions\ResellerClubApiException;

try {
    $rc->domains()->getDetailsByDomain('unknown-domain.com');
} catch (AuthenticationException $e) {
    // 401 Unauthorized or IP whitelist rejected
} catch (ResourceNotFoundException $e) {
    // 404 Entity/Order not found
} catch (ValidationException $e) {
    // 400 Mandatory parameters missing or invalid format
} catch (ResellerClubApiException $e) {
    // General API error
    $status = $e->getHttpStatusCode();
    $data = $e->getResponseData();
}
```

---

## References

- [ResellerClub HTTP API KnowledgeBase (KB 744)](https://resellerclub.webpropanel.com/kb/answer/744)
- [LogicBoxes API Documentation (Node 751)](https://manage.logicboxes.com/kb/node/751)

---

## License

This project is licensed under the [MIT License](LICENSE).