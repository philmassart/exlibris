<?php
namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route ("/", name="home")
     * @param BookRepository $repository
     * @return Response
     */
    public function index(BookRepository $repository): Response
    {
        $books = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'books' => $books

        ]);
    }
}