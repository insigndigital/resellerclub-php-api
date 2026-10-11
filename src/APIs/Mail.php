<?php

declare(strict_types=1);

namespace ResellerClub\APIs;

use ResellerClub\Exceptions\ResellerClubApiException;
use ResellerClub\Helper;

/**
 * Class Mail
 *
 * Covers Enterprise and Business Email Hosting operations per ResellerClub KB 744.
 *
 * @package ResellerClub\APIs
 * @link https://resellerclub.webpropanel.com/kb/answer/744
 */
class Mail
{
    use Helper;

    protected string $api = 'mail';

    /**
     * Add an Email Hosting Order (Business/Enterprise Email).
     *
     * @param string $domain Domain name (e.g., example.com)
     * @param int $customerId Customer ID
     * @param int $months Duration in months
     * @param int $numberOfAccounts Number of email mailboxes/accounts
     * @param string $plan Plan identifier (e.g., 'enterpriseemailus', 'eeliteus')
     * @param string $location Location (e.g. 'us', 'in')
     * @param array $extra Optional parameters (invoice-option, etc.)
     *
     * @throws ResellerClubApiException
     */
    public function addDomain(
        string $domain,
        int $customerId,
        int $months,
        int $numberOfAccounts,
        string $plan = 'enterpriseemailus',
        string $location = 'us',
        array $extra = []
    ): mixed {
        $params = array_merge([
            'domain-name' => $domain,
            'customer-id' => $customerId,
            'months' => $months,
            'no-of-accounts' => $numberOfAccounts,
            'plan-id' => $plan,
            'location' => $location,
            'invoice-option' => $extra['invoice-option'] ?? 'NoInvoice',
        ], $extra);

        return $this->post('domain/add', $this->fillParameters($params));
    }

    /**
     * Gets details of an email order.
     *
     * @throws ResellerClubApiException
     */
    public function details(int $orderId): mixed
    {
        return $this->get('domain/details', ['order-id' => $orderId]);
    }

    /**
     * Add an email account (mailbox) to an active email order.
     *
     * @throws ResellerClubApiException
     */
    public function addUser(
        int $orderId,
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        string $notificationEmail = ''
    ): mixed {
        return $this->post('user/add', $this->fillParameters([
            'order-id' => $orderId,
            'email' => $email,
            'passwd' => $password,
            'first-name' => $firstName,
            'last-name' => $lastName,
            'notification-email' => $notificationEmail,
        ]));
    }

    /**
     * Changes password for an email account.
     *
     * @throws ResellerClubApiException
     */
    public function changePassword(int $orderId, string $email, string $newPassword): mixed
    {
        return $this->post('user/modify-password', [
            'order-id' => $orderId,
            'email' => $email,
            'new-passwd' => $newPassword,
        ]);
    }

    /**
     * Deletes an email account.
     *
     * @throws ResellerClubApiException
     */
    public function deleteUser(int $orderId, string $email): mixed
    {
        return $this->post('user/delete', [
            'order-id' => $orderId,
            'email' => $email,
        ]);
    }

    /**
     * Adds an email forwarder.
     *
     * @param int $orderId
     * @param string $email Source email
     * @param array $forwardAddresses Destination emails
     *
     * @throws ResellerClubApiException
     */
    public function addForward(int $orderId, string $email, array $forwardAddresses): mixed
    {
        return $this->post('user/add-forward', [
            'order-id' => $orderId,
            'email' => $email,
            'forward-email' => $forwardAddresses,
        ]);
    }

    /**
     * Deletes an email forwarder.
     *
     * @throws ResellerClubApiException
     */
    public function deleteForward(int $orderId, string $email, array $forwardAddresses): mixed
    {
        return $this->post('user/delete-forward', [
            'order-id' => $orderId,
            'email' => $email,
            'forward-email' => $forwardAddresses,
        ]);
    }
}

