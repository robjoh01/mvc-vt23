<?php

namespace App\Controller;

use PHPUnit\Framework\TestCase;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Test cases for class APIController.
 */
class APIControllerTest extends TestCase
{
    public function testApiDeck(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturn([]);

        $apiController = new APIController();
        $response = $apiController->apiDeck($sessionMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testApiDeckShuffle(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturn([]);

        $apiController = new APIController();
        $response = $apiController->apiDeckShuffle($sessionMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetDailyQuote(): void
    {
        $apiController = new APIController();
        $quote = $apiController->getDailyQuote();

        $this->assertIsString($quote);
        $this->assertNotEmpty($quote);
    }

    public function testQuote(): void
    {
        $apiController = new APIController();
        $response = $apiController->quote();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCurrentStatusOfCardGame(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturn(null);

        $apiController = new APIController();
        $response = $apiController->getCurrentStatusOfCardGame($sessionMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}