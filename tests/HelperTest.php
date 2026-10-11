<?php

declare(strict_types=1);

namespace ResellerClub\Tests;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ResellerClub\APIs\Products;
use ResellerClub\Exceptions\AuthenticationException;
use ResellerClub\Exceptions\ResellerClubApiException;
use ResellerClub\Exceptions\ResourceNotFoundException;

class HelperTest extends TestCase
{
    private function createProductsWithMock(array $responses): Products
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Guzzle(['handler' => $handlerStack]);

        return new Products($guzzle, ['auth-userid' => '123456', 'api-key' => 'mock_key']);
    }

    public function testSuccessfulJsonResponse(): void
    {
        $products = $this->createProductsWithMock([
            new Response(200, [], json_encode(['status' => 'Success', 'plans' => ['plan1', 'plan2']])),
        ]);

        $result = $products->categoryKeysMapping();
        $this->assertIsArray($result);
        $this->assertEquals('Success', $result['status']);
    }

    public function testThrowsAuthenticationExceptionOnAuthError(): void
    {
        $this->expectException(AuthenticationException::class);

        $products = $this->createProductsWithMock([
            new Response(401, [], json_encode(['status' => 'ERROR', 'message' => 'Authentication failed'])),
        ]);

        $products->customerPrice();
    }

    public function testThrowsResourceNotFoundExceptionOnMissingResource(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        $products = $this->createProductsWithMock([
            new Response(404, [], json_encode(['status' => 'ERROR', 'message' => 'Order id does not exist'])),
        ]);

        $products->details();
    }

    public function testThrowsApiExceptionOnServerError(): void
    {
        $this->expectException(ResellerClubApiException::class);

        $products = $this->createProductsWithMock([
            new Response(500, [], json_encode(['status' => 'ERROR', 'message' => 'Internal Server Error'])),
        ]);

        $products->customerPrice();
    }
}

