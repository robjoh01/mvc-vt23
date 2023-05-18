<?php

namespace App\Controller;

use App\Entity\Product;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Product.
 */
class ProductTest extends TestCase
{
    public function testId(): void
    {
        $product = new Product();
        $this->assertNull($product->getId());
    }

    public function testName(): void
    {
        $product = new Product();
        $this->assertNull($product->getName());

        $product->setName('Test Product');
        $this->assertSame('Test Product', $product->getName());
    }

    public function testValue(): void
    {
        $product = new Product();
        $this->assertNull($product->getValue());

        $product->setValue(100);
        $this->assertSame(100, $product->getValue());
    }
}