<?php

namespace App\Controller;

use App\Entity\Book;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Book.
 */
class BookTest extends TestCase
{
    public function testId(): void
    {
        $book = new Book();
        $this->assertNull($book->getId());
    }

    public function testTitle(): void
    {
        $book = new Book();
        $this->assertNull($book->getTitle());

        $book->setTitle('Test Title');
        $this->assertSame('Test Title', $book->getTitle());
    }

    public function testISBN(): void
    {
        $book = new Book();
        $this->assertNull($book->getISBN());

        $book->setISBN('1234567890');
        $this->assertSame('1234567890', $book->getISBN());
    }

    public function testAuthor(): void
    {
        $book = new Book();
        $this->assertNull($book->getAuthor());

        $book->setAuthor('John Doe');
        $this->assertSame('John Doe', $book->getAuthor());
    }

    public function testImgPath(): void
    {
        $book = new Book();
        $this->assertNull($book->getImgPath());

        $book->setImgPath('/path/to/image.jpg');
        $this->assertSame('/path/to/image.jpg', $book->getImgPath());
    }
}