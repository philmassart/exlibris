<?php
namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminBookController extends AbstractController {

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $books = $this->repository->findAll();
        return $this->render('admin/book/index.html.twig', compact('books'));
    }

    /**
     * @Route("/admin/book/create", name="admin.book.new")
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