<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookController extends AbstractController
{
    #[Route('/library', name: 'book_library')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig');
    }

    #[Route('/library/creator', name: 'book_creator')]
    public function bookCreator(
    ): Response {
        return $this->render('book/creator.html.twig');
    }

    #[Route('/library/create', name: 'book_create', methods: ['POST'])]
    public function createBook(
        ManagerRegistry $doctrine,
        Request $request,
    ): Response {
        /** @var string */
        $title = $request->request->get('title');

        /** @var string */
        $author = $request->request->get('author');

        /** @var string */
        $isbn = $request->request->get('isbn');

        /** @var string */
        $imgPath = $request->request->get('img_path');

        $entityManager = $doctrine->getManager();

        $book = new Book();

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImgPath($imgPath);

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('book_create_success');
    }

    #[Route('/library/create', name: 'book_create_success')]
    public function createBookSuccess(
    ): Response {
        return $this->redirectToRoute('book_list');
    }

    #[Route('/library/list', name: 'book_list')]
    public function showBookList(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $library = [];

        foreach ($books as $book) {
            $book = [
                "id" => $book->getId(),
                "title" => $book->getTitle(),
                "author" => $book->getAuthor(),
                "isbn" => $book->getIsbn(),
                "img_path" => $book->getImgPath()
            ];

            $library[] = $book;
        };

        $data = [
            'books' => $library
        ];

        return $this->render('book/list.html.twig', $data);
    }

    #[Route('/library/book/{id}', name: 'book')]
    public function showBook(
        BookRepository $bookRepository,
        int $id,
    ): Response {
        /** @var Book */
        $book = $bookRepository->find($id);

        $data = [
            "id" => $book->getId(),
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "isbn" => $book->getIsbn(),
            "img_path" => $book->getImgPath()
        ];

        return $this->render('book/book.html.twig', $data);
    }

    #[Route('/library/update', name: 'book_update', methods: ['POST'])]
    public function updateBook(
        BookRepository $bookRepository,
        Request $request
    ): Response {
        $bookId = (int)$request->request->get('id');

        /** @var Book */
        $book = $bookRepository->find($bookId);

        if ($book == null) {
            return $this->redirectToRoute('book_list');
        }

        /** @var string */
        $title = $request->request->get('title');

        /** @var string */
        $author = $request->request->get('author');

        /** @var string */
        $isbn = $request->request->get('isbn');

        /** @var string */
        $imgPath = $request->request->get('img_path');

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImgPath($imgPath);

        $bookRepository->save($book, true);

        return $this->redirectToRoute('book_list');
    }

    #[Route('/library/delete', name: 'book_delete', methods: ['POST'])]
    public function deleteBook(
        BookRepository $bookRepository,
        Request $request
    ): Response {
        $bookId = (int)$request->request->get('id');
        $book = $bookRepository->find($bookId);

        if (!$book) {
            return $this->redirectToRoute("book_list");
        }

        $bookRepository->remove($book, true);

        return $this->redirectToRoute("book_list");
    }
}
