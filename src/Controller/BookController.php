<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Form\BookSearchType;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private readonly BookRepository $repository, EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route(
     *     "admin/livres",
     *     name="admin.book.index"
     * )
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new BookSearch();
        $form = $this->createForm(BookSearchType::class, $search)
            ->handleRequest($request);

        $books = $paginator->paginate(
            $this->repository->findAllVisibleQuery($this->getUser(), $search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('book/index.html.twig', [
            'current_menu' => 'books',
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     *     "/admin/livres/{slug}-{id}",
     *     name="admin.book.show",
     *      requirements={"slug": "[a-z0-9\-]*"}
     * )
     */
    public function show(Book $book, string $slug): Response
    {
        if ($book->getSlug() !== $slug) {
            return $this->redirectToRoute('book.show', [
                'id' => $book->getId(),
                'slug' => $book->getSlug()
            ], 301);
        }
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'current_menu' => 'livres'
        ]);
    }

    /**
     * @Route(
     *     "/admin/book/create",
     *     name="admin.book.new"
     * )
     */
    public function new(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        $book = new Book();
        $book->setUser($this->getUser());
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();
            $this->addFlash('success', 'Créé avec succès');
            return $this->redirectToRoute('admin.book.index');
        }
        return $this->render('admin/book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route (
     *     "/admin/book/{id}",
     *     name="admin.book.edit",
     *     methods="GET|POST"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Book $book, Request $request)
    {

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Modifié avec succès');
            return $this->redirectToRoute('admin.book.show', [
                'id' => $book->getId(),
                'slug' => $book->getSlug()
            ], 301);
        }

        return $this->render('admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route (
     *     "/admin/book/{id}",
     *     name="admin.book.delete",
     *     methods="DELETE"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Book $book, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->get('_token'))) {
            $this->em->remove($book);
            $this->em->flush();
            $this->addFlash('success', 'Supprimé avec succès');
        }
        return $this->redirectToRoute('admin.book.index');
    }

}