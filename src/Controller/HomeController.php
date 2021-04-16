<?php

namespace App\Controller;
use App\Entity\Books;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;


class HomeController extends AbstractController
{
    private $request;
    public function __construct(RequestStack $request){

        $this->request = $request;
    }

   
    /**
     * @Route("/index", name="home")
     */
    public function index(): Response
    {
        $session = new Session();
        $session->start();

        //get queryString
        $catid = $this->request->getCurrentRequest()->query->get('category');

        if($catid == 0){
            $book = $this->getDoctrine()
            ->getRepository(Books::class)
            ->findAll();
        }else{
            $book = $this->getDoctrine()
            ->getRepository(Books::class)
            ->findby(
                ['categoryId' => $catid]
            );
        }

        
        
        $cat = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        
        return $this->render('home/index.html.twig', [
            'books' => $book,
            'cats' => $cat,
            'catId'=> $this->request->getCurrentRequest()->query->get('category')
           
        ]);
    }
}
