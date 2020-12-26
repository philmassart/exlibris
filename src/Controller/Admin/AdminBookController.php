<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Form\BookSearchType;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminBookController extends AbstractController
{

    /**
     * @var  BookRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(BookRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.book.index")
     * @param BookRepository $bookRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request)
    {
        //$books = $this->repository->findAll();
        $search = new BookSearch();
        $form = $this->createForm(BookSearchType::class, $search);
        $form->handleRequest($request);

        $books = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 12
        );

        return $this->render('admin/book/index.html.twig', [
            'books' => $books,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/admin/book/create", name="admin.book.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $book = new Book();
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
     * @Route ("/admin/book/{id}", name="admin.book.edit", methods="GET|POST")
     * @param Book $book
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Book $book, Request $request)
    {

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Modifié avec succès');
            return $this->redirectToRoute('admin.book.index');
        }

        return $this->render('admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/admin/book/{id}", name="admin.book.delete", methods="DELETE")
     * @param Book $book
     * @param Request $request
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