<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'author_index', methods: ['GET'])]
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AuthorRepository $authorRepository, #[Autowire('%authors_Photo_Dir%')] string $authorsPhotoDir): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($photo = $form->get('image')->getData()){
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($authorsPhotoDir . DIRECTORY_SEPARATOR . $author->getLastname() . '-' . $author->getFirstname(), $filename);
                }catch (FileException $e){

                }
                $author->setImage($filename);
                $authorRepository->save($author, true);
            }

            return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('author/new.html.twig', [
            'author' => $author,
            'form' => $form,
            'activeController' => 'author',
        ]);
    }

    #[Route('/{id}', name: 'author_show', methods: ['GET'])]
    public function show(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
            'activeController' => 'author',
        ]);
    }

    #[Route('/edit/{id}', name: 'author_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Author $author, AuthorRepository $authorRepository, #[Autowire('%authors_Photo_Dir%')] string $authorsPhotoDir): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($photo = $form->get('image')->getData()){
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($authorsPhotoDir . DIRECTORY_SEPARATOR . $author->getLastname() . '-' . $author->getFirstname(), $filename);
                }catch (FileException $e){

                }
                $author->setImage($filename);
                $authorRepository->save($author, true);
            }
            return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('author/edit.html.twig', [
            'author' => $author,
            'form' => $form,
            'activeController' => 'author',
        ]);
    }

    #[Route('/delete/{id}', name: 'author_delete', methods: ['POST'])]
    public function delete(Request $request, Author $author, AuthorRepository $authorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $authorRepository->remove($author, true);
        }

        return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
    }
}
