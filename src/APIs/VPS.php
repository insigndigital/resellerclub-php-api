<?php

declare(strict_types=1);

namespace ResellerClub\APIs;

use ResellerClub\Exceptions\ResellerClubApiException;
use ResellerClub\Helper;

/**
 * Class VPS
 *
 * Covers VPS KVM and Cloud Virtual Server operations per ResellerClub KB 744.
 *
 * @package ResellerClub\APIs
 * @link https://resellerclub.webpropanel.com/kb/answer/744
 */
class VPS
{
    use Helper;

    protected string $api = 'vps/linux';

    /**
     * Add a VPS Linux KVM Order.
     *
     * @param string $domain Domain or hostname
     * @param int $customerId Customer ID
     * @param int $months Duration in months
     * @param string $plan Plan identifier
     * @param string $location Location (e.g. 'us', 'in')
     * @param array $extra Optional parameters (addon-cpanel, invoice-option, etc.)
     *
     * @throws ResellerClubApiException
     */
    public function add(
        string $domain,
        int $customerId,
        int $months,
        string $plan,
        string $location = 'us',
        array $extra = []
    ): mixed {
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
     * Gets details of a VPS order.
     *
     * @throws ResellerClubApiException
     */
    public function details(int $orderId): mixed
    {
        return $this->get('details', ['order-id' => $orderId]);
    }

    /**
     * Reboots a VPS server.
     *
     * @throws ResellerClubApiException
     */
    public function reboot(int $orderId): mixed
    {
        return $this->post('reboot', ['order-id' => $orderId]);
    }

    /**
     * Starts a VPS server.
     *
     * @throws ResellerClubApiException
     */
    public function start(int $orderId): mixed
    {
        return $this->post('start', ['order-id' => $orderId]);
    }

    /**
     * Stops a VPS server.
     *
     * @throws ResellerClubApiException
     */
    public function stop(int $orderId): mixed
    {
        return $this->post('stop', ['order-id' => $orderId]);
    }

    /**
     * Renews a VPS order.
     *
     * @throws ResellerClubApiException
     */
    public function renew(int $orderId, int $months, string $invoiceOption = 'NoInvoice'): mixed
    {
        return $this->post('renew', [
            'order-id' => $orderId,
            'months' => $months,
            'invoice-option' => $invoiceOption,
        ]);
    }

    /**
     * Deletes a VPS order.
     *
     * @throws ResellerClubApiException
     */
    public function delete(int $orderId): mixed
    {
        return $this->post('delete', ['order-id' => $orderId]);
    }
}
