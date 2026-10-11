<?php

declare(strict_types=1);

namespace ResellerClub\APIs;

use ResellerClub\Exceptions\ResellerClubApiException;
use ResellerClub\Helper;

/**
 * Class Hosting
 *
 * Covers Web Hosting (Single Domain, Multi Domain, Reseller, Cloud) operations per ResellerClub KB 744.
 *
 * @package ResellerClub\APIs
 * @link https://resellerclub.webpropanel.com/kb/answer/744
 */
class Hosting
{
    use Helper;

    protected string $api = '';

    /**
     * Add a Single Domain Linux Hosting order.
     *
     * @param string $domain Domain name (e.g., example.com)
     * @param int $customerId Customer ID in ResellerClub
     * @param int $months Duration in months (e.g., 1, 3, 6, 12)
     * @param string $plan Plan identifier from category mapping (e.g., 'plan1')
     * @param string $location Server location code (e.g., 'us', 'in', 'uk')
     * @param array $extra Additional optional parameters (invoice-option, etc.)
     *
     * @throws ResellerClubApiException
     */
    public function addLinuxSingle(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
        $this->api = 'singledomainhosting/linux';
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('add', $this->fillParameters($params));
    }

    /**
     * Add a Single Domain Windows Hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function addWindowsSingle(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
        $this->api = 'singledomainhosting/windows';
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('add', $this->fillParameters($params));
    }

    /**
     * Add a Multi Domain Linux Hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function addLinuxMulti(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
        $this->api = 'multidomainhosting/linux';
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('add', $this->fillParameters($params));
    }

    /**
     * Add a Multi Domain Windows Hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function addWindowsMulti(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
        $this->api = 'multidomainhosting/windows';
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('add', $this->fillParameters($params));
    }

    /**
     * Add a Reseller Hosting Linux order.
     *
     * @throws ResellerClubApiException
     */
    public function addLinuxReseller(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
        $this->api = 'resellerhosting/linux';
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('add', $this->fillParameters($params));
    }

    /**
     * Add a Cloud Hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function addCloudHosting(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
        $this->api = 'cloudhosting';
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('add', $this->fillParameters($params));
    }

    /**
     * Gets details of a Single Domain Linux hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function detailsLinuxSingle(int $orderId): mixed
    {
        $this->api = 'singledomainhosting/linux';
        return $this->get('details', ['order-id' => $orderId]);
    }

    /**
     * Gets details of a Single Domain Windows hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function detailsWindowsSingle(int $orderId): mixed
    {
        $this->api = 'singledomainhosting/windows';
        return $this->get('details', ['order-id' => $orderId]);
    }

    /**
     * Gets details of a Multi Domain Linux hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function detailsLinuxMulti(int $orderId): mixed
    {
        $this->api = 'multidomainhosting/linux';
        return $this->get('details', ['order-id' => $orderId]);
    }

    /**
     * Gets details of a Multi Domain Windows hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function detailsWindowsMulti(int $orderId): mixed
    {
        $this->api = 'multidomainhosting/windows';
        return $this->get('details', ['order-id' => $orderId]);
    }

    /**
     * Renews a hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function renew(string $serviceType, int $orderId, int $months, string $invoiceOption = 'NoInvoice'): mixed
    {
        $this->api = $serviceType;
        return $this->post('renew', [
            'order-id' => $orderId,
            'months' => $months,
            'invoice-option' => $invoiceOption,
        ]);
    }

    /**
     * Deletes a hosting order.
     *
     * @throws ResellerClubApiException
     */
    public function delete(string $serviceType, int $orderId): mixed
    {
        $this->api = $serviceType;
        return $this->post('delete', ['order-id' => $orderId]);
    }
}
