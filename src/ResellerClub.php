<?php

declare(strict_types=1);

namespace ResellerClub;

use GuzzleHttp\Client as Guzzle;
use ResellerClub\APIs\Actions;
use ResellerClub\APIs\Billing;
use ResellerClub\APIs\Contacts;
use ResellerClub\APIs\Country;
use ResellerClub\APIs\Currency;
use ResellerClub\APIs\Customers;
use ResellerClub\APIs\DNS;
use ResellerClub\APIs\Domains;
use ResellerClub\APIs\Hosting;
use ResellerClub\APIs\Mail;
use ResellerClub\APIs\Orders;
use ResellerClub\APIs\Products;
use ResellerClub\APIs\VPS;

/**
 * Class ResellerClub
 *
 * Modern PHP SDK for the ResellerClub / LogicBoxes HTTP API (KB 744).
 *
 * @package ResellerClub
 * @link https://resellerclub.webpropanel.com/kb/answer/744
 */
class ResellerClub
{
    public const API_URL = 'https://httpapi.com/api/';
    public const API_TEST_URL = 'https://test.httpapi.com/api/';

    private Guzzle $guzzle;
    private array $apiList = [];
    private array $authentication = [];

    /**
     * ResellerClub client constructor.
     *
     * @param int|string $userId Reseller User ID (auth-userid)
     * @param string $apiKey Reseller API Key (api-key)
     * @param bool $testMode True to use the test/demo environment (test.httpapi.com)
     * @param float $timeout Request timeout in seconds
     * @param string $bindIp Outbound IP address to bind (for multi-IP servers)
     */
    public function __construct(
        int|string $userId,
        string $apiKey,
        bool $testMode = false,
        float $timeout = 30.0,
        string $bindIp = ''
    ) {
        $this->authentication = [
            'auth-userid' => (string) $userId,
            'api-key' => $apiKey,
        ];

        $guzzleConfig = [
            'base_uri' => $testMode ? self::API_TEST_URL : self::API_URL,
            'verify' => true,
            'connect_timeout' => $timeout,
            'timeout' => $timeout,
        ];

        if (!empty($bindIp)) {
            $guzzleConfig['curl'] = [CURLOPT_INTERFACE => $bindIp];
            $guzzleConfig['stream_context'] = ['socket' => ['bindto' => $bindIp]];
        }

        $this->guzzle = new Guzzle($guzzleConfig);
    }

    /**
     * Internal factory for API resource modules.
     */
    private function getAPI(string $api): mixed
    {
        if (empty($this->apiList[$api])) {
            $class = '\\ResellerClub\\APIs\\' . $api;
            $this->apiList[$api] = new $class($this->guzzle, $this->authentication);
        }

        return $this->apiList[$api];
    }

    /**
     * Domain management (Availability, Register, Transfer, Renew, Locks, DNSSEC, Whois).
     */
    public function domains(): Domains
    {
        return $this->getAPI('Domains');
    }

    /**
     * Web Hosting management (Single Domain, Multi Domain, Reseller, Cloud).
     */
    public function hosting(): Hosting
    {
        return $this->getAPI('Hosting');
    }

    /**
     * VPS and Cloud Virtual Server management.
     */
    public function vps(): VPS
    {
        return $this->getAPI('VPS');
    }

    /**
     * Business & Enterprise Email Hosting management.
     */
    public function mail(): Mail
    {
        return $this->getAPI('Mail');
    }

    /**
     * Managed DNS record management (A, CNAME, MX, TXT, etc.).
     */
    public function dns(): DNS
    {
        return $this->getAPI('DNS');
    }

    /**
     * Contact management (Registrant, Admin, Tech, Billing contacts).
     */
    public function contacts(): Contacts
    {
        return $this->getAPI('Contacts');
    }

    /**
     * Customer management (Signup, Modify, Details, Passwords).
     */
    public function customers(): Customers
    {
        return $this->getAPI('Customers');
    }

    /**
     * Products catalog & pricing (Customer, Reseller, Cost pricing, Plan details).
     */
    public function products(): Products
    {
        return $this->getAPI('Products');
    }

    /**
     * Order lifecycle operations (Suspend, Unsuspend).
     */
    public function orders(): Orders
    {
        return $this->getAPI('Orders');
    }

    /**
     * Reseller billing & accounting.
     */
    public function billing(): Billing
    {
        return $this->getAPI('Billing');
    }

    /**
     * Async task and action queue checks.
     */
    public function actions(): Actions
    {
        return $this->getAPI('Actions');
    }

    /**
     * Country lookup utilities.
     */
    public function country(): Country
    {
        return $this->getAPI('Country');
    }

    /**
     * Currency lookup utilities.
     */
    public function currency(): Currency
    {
        return $this->getAPI('Currency');
    }
}
