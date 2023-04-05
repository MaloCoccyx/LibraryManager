<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
            'activeController' => 'book',
        ]);
    }

    #[Route('/new', name: 'book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookRepository $bookRepository, #[Autowire('%books_Photo_Dir%')] string $booksPhotoDir): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($photo = $form->get('image')->getData()){
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($booksPhotoDir . DIRECTORY_SEPARATOR . $book->getId() .'-' . $book->getTitle(), $filename);
                }catch (FileException $e){

                }
                $book->setImage($filename);
            }
            $bookRepository->save($book, true);

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
            'activeController' => 'book',
        ]);
    }

    #[Route('/{id}', name: 'book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'activeController' => 'book',
        ]);
    }

    #[Route('/edit/{id}', name: 'book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, BookRepository $bookRepository, #[Autowire('%books_Photo_Dir%')] string $booksPhotoDir): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($photo = $form->get('image')->getData()){
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($booksPhotoDir . DIRECTORY_SEPARATOR . $book->getId() .'-' . $book->getTitle(), $filename);
                }catch (FileException $e){

                }
                $book->setImage($filename);
            }
            $bookRepository->save($book, true);

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
            'activeController' => 'book',
        ]);
    }

    #[Route('/delete/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $bookRepository->remove($book, true);
        }

        return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
    }
}
