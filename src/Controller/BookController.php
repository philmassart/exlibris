<?php
namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @var BookRepository
     */
    private $repository;

    public function __construct(BookRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/livres", name="book.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig',[
            'current_menu' => 'livres'
        ]);
    }

    /**
     * @Route("/livres/{slug}-{id}", name="book.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Book $book, string $slug): Response
    {
        if ($book->getSlug() !== $slug) {
            return $this->redirectToRoute('book.show',[
                'id' => $book->getId(),
                'slug' => $book->getSlug()
            ], 301);
        }
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'current_menu' => 'livres'
        ]);
    }

}