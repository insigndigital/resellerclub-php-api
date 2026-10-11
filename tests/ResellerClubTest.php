<?php

declare(strict_types=1);

namespace ResellerClub\Tests;

use PHPUnit\Framework\TestCase;
use ResellerClub\APIs\DNS;
use ResellerClub\APIs\Domains;
use ResellerClub\APIs\Hosting;
use ResellerClub\APIs\Mail;
use ResellerClub\APIs\Products;
use ResellerClub\APIs\VPS;
use ResellerClub\ResellerClub;

class ResellerClubTest extends TestCase
{
    private ResellerClub $client;

    protected function setUp(): void
    {
        $this->client = new ResellerClub('123456', 'mock_api_key', true);
    }

    public function testInstantiatesResourceModules(): void
    {
        $this->assertInstanceOf(Domains::class, $this->client->domains());
        $this->assertInstanceOf(Hosting::class, $this->client->hosting());
        $this->assertInstanceOf(VPS::class, $this->client->vps());
        $this->assertInstanceOf(Mail::class, $this->client->mail());
        $this->assertInstanceOf(DNS::class, $this->client->dns());
        $this->assertInstanceOf(Products::class, $this->client->products());
    }

    public function testReusesInstantiatedModuleInstances(): void
    {
        $first = $this->client->hosting();
        $second = $this->client->hosting();
        $this->assertSame($first, $second);
    }
}

