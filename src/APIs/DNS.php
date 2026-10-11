<?php

declare(strict_types=1);

namespace ResellerClub\APIs;

use ResellerClub\Exceptions\ResellerClubApiException;
use ResellerClub\Helper;

/**
 * Class DNS
 *
 * Covers Managed DNS Record operations per ResellerClub KB 744.
 *
 * @package ResellerClub\APIs
 * @link https://resellerclub.webpropanel.com/kb/answer/744
 */
class DNS
{
    use Helper;

    protected string $api = 'dns/manage';

    /**
     * Add an IPv4 (A) record.
     *
     * @throws ResellerClubApiException
     */
    public function addA(string $domain, string $value, string $host = '', int $ttl = 14400): mixed
    {
        return $this->post('add-ipv4-record', $this->fillParameters([
            'domain-name' => $domain,
            'value' => $value,
            'host' => $host,
            'ttl' => $ttl,
        ]));
    }

    /**
     * Add a CNAME record.
     *
     * @throws ResellerClubApiException
     */
    public function addCname(string $domain, string $value, string $host = '', int $ttl = 14400): mixed
    {
        return $this->post('add-cname-record', $this->fillParameters([
            'domain-name' => $domain,
            'value' => $value,
            'host' => $host,
            'ttl' => $ttl,
        ]));
    }

    /**
     * Add an MX record.
     *
     * @throws ResellerClubApiException
     */
    public function addMx(string $domain, string $value, int $priority = 10, string $host = '', int $ttl = 14400): mixed
    {
        return $this->post('add-mx-record', $this->fillParameters([
            'domain-name' => $domain,
            'value' => $value,
            'priority' => $priority,
            'host' => $host,
            'ttl' => $ttl,
        ]));
    }

    /**
     * Add a TXT record.
     *
     * @throws ResellerClubApiException
     */
    public function addTxt(string $domain, string $value, string $host = '', int $ttl = 14400): mixed
    {
        return $this->post('add-txt-record', $this->fillParameters([
            'domain-name' => $domain,
            'value' => $value,
            'host' => $host,
            'ttl' => $ttl,
        ]));
    }

    /**
     * Search DNS records for a domain.
     *
     * @throws ResellerClubApiException
     */
    public function search(string $domain, string $type = '', int $noOfRecords = 50, int $pageNo = 1): mixed
    {
        return $this->get('search-records', $this->fillParameters([
            'domain-name' => $domain,
            'type' => $type,
            'no-of-records' => $noOfRecords,
            'page-no' => $pageNo,
        ]));
    }

    /**
     * Deletes a DNS record.
     *
     * @throws ResellerClubApiException
     */
    public function delete(string $domain, string $type, string $currentValue, string $host = ''): mixed
    {
        $endpoint = match (strtolower($type)) {
            'a' => 'delete-ipv4-record',
            'aaaa' => 'delete-ipv6-record',
            'cname' => 'delete-cname-record',
            'mx' => 'delete-mx-record',
            'txt' => 'delete-txt-record',
            'ns' => 'delete-ns-record',
            default => 'delete-ipv4-record',
        };

        return $this->post($endpoint, $this->fillParameters([
            'domain-name' => $domain,
            'value' => $currentValue,
            'host' => $host,
        ]));
    }
}

