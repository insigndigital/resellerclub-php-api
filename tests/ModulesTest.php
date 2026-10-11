<?php

declare(strict_types=1);

namespace ResellerClub\Tests;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ResellerClub\APIs\DNS;
use ResellerClub\APIs\Hosting;
use ResellerClub\APIs\Mail;
use ResellerClub\APIs\VPS;

class ModulesTest extends TestCase
{
    private function createClientWithMock(string $class, array $responses)
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Guzzle(['handler' => $handlerStack]);

        return new $class($guzzle, ['auth-userid' => '123456', 'api-key' => 'mock_key']);
    }

    public function testHostingAddLinuxSingle(): void
    {
        /** @var Hosting $hosting */
        $hosting = $this->createClientWithMock(Hosting::class, [
            new Response(200, [], json_encode(['status' => 'Success', 'entityid' => 999])),
        ]);

        $res = $hosting->addLinuxSingle('example.com', 123, 12, 'plan1', 'us');
        $this->assertIsArray($res);
        $this->assertEquals('Success', $res['status']);
        $this->assertEquals(999, $res['entityid']);
    }

    public function testVPSReboot(): void
    {
        /** @var VPS $vps */
        $vps = $this->createClientWithMock(VPS::class, [
            new Response(200, [], json_encode(['status' => 'Success', 'action' => 'reboot'])),
        ]);

        $res = $vps->reboot(888);
        $this->assertIsArray($res);
        $this->assertEquals('Success', $res['status']);
    }

    public function testMailAddUser(): void
    {
        /** @var Mail $mail */
        $mail = $this->createClientWithMock(Mail::class, [
            new Response(200, [], json_encode(['status' => 'Success', 'email' => 'user@example.com'])),
        ]);

        $res = $mail->addUser(777, 'user@example.com', 'Secret123!', 'John', 'Doe');
        $this->assertIsArray($res);
        $this->assertEquals('Success', $res['status']);
    }

    public function testDnsAddA(): void
    {
        /** @var DNS $dns */
        $dns = $this->createClientWithMock(DNS::class, [
            new Response(200, [], json_encode(['status' => 'Success'])),
        ]);

        $res = $dns->addA('example.com', '192.168.1.1', 'www');
        $this->assertIsArray($res);
        $this->assertEquals('Success', $res['status']);
    }
}
