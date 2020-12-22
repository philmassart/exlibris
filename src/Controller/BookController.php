<?php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Form\BookSearchType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new BookSearch();
        $form = $this->createForm(BookSearchType::class, $search);
        $form->handleRequest($request);

        $books = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 12
        );
        return $this->render('book/index.html.twig',[
            'current_menu' => 'books',
            'books' => $books,
            'form' => $form->createView()
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