<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/author")
 */
class AdminAuthorController extends AbstractController
{
    /**
     * @Route("/", name="admin.author.index", methods={"GET"})
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('/admin/author/index.html.twig', [
            'authors' => $authorRepository->myFindAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.author.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('admin.author.index');
        }

        return $this->render('admin/author/new.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.author.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Author $author
     * @return Response
     */
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.author.index');
        }

        return $this->render('admin/author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.author.delete", methods={"DELETE"})
     * @param Request $request
     * @param Author $author
     * @return Response
     */
    public function delete(Request $request, Author $author): Response
    {
        if ($this->isCsrfTokenValid('admin/delete'.$author->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($author);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.author.index');
    }
}
