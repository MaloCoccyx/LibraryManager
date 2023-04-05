<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'publisher_index', methods: ['GET'])]
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publishers' => $publisherRepository->findAll(),
            'activeController' => 'publisher',
        ]);
    }

    #[Route('/new', name: 'publisher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PublisherRepository $publisherRepository): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherRepository->save($publisher, true);

            return $this->redirectToRoute('publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
            'activeController' => 'publisher',
        ]);
    }

    #[Route('/{id}', name: 'publisher_show', methods: ['GET'])]
    public function show(Publisher $publisher): Response
    {
        return $this->render('publisher/show.html.twig', [
            'publisher' => $publisher,
            'activeController' => 'publisher',
        ]);
    }

    #[Route('/edit/{id}', name: 'publisher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publisher $publisher, PublisherRepository $publisherRepository): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherRepository->save($publisher, true);

            return $this->redirectToRoute('publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
            'activeController' => 'publisher',
        ]);
    }

    #[Route('/delete/{id}', name: 'publisher_delete', methods: ['POST'])]
    public function delete(Request $request, Publisher $publisher, PublisherRepository $publisherRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->request->get('_token'))) {
            $publisherRepository->remove($publisher, true);
        }

        return $this->redirectToRoute('publisher_index', [], Response::HTTP_SEE_OTHER);
    }
}
