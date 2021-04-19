<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Books;
use App\Entity\Category;
class BookController extends AbstractController
{
    private $request;
    public function __construct(RequestStack $request){

        $this->request = $request;
    }

    /**
     * @Route("admin/books", name="book")
     */
    public function index(): Response
    {

        //get category id from url 
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

        return $this->render('book/index.html.twig', [
            'books' => $book,
            'cats' => $cat,
            'catId'=> $catid
        ]);
    }

    

    /**
     * @Route("admin/books/{id}", name="book_row" , methods={"GET"})
     */
    public function getbookRow(string $id): Response{

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 
       

        $sql = "SELECT *, books.id AS bookid FROM books LEFT JOIN category ON category.id=books.category_id WHERE books.id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $book = $stmt->fetchAllAssociative(); 
        
        return $this->json(['book' => $book]);
    }


    /**
     * @Route("admin/books/edit", name="book_edit" , methods={"POST"})
     */
    public function editBook(Request $request): Response{
        

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

        $id = $request->get('id');
		$isbn = $request->get('isbn');
		$title = $request->get('title');
		$category = $request->get('category');
		$author = $request->get('author');
		$publisher = $request->get('publisher');
		$pub_date = $request->get('pub_date');
        
        $sql = "UPDATE books SET isbn = '$isbn', title = '$title', category_id = '$category', author = '$author', publisher = '$publisher', publish_date = '$pub_date' WHERE id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        return $this->redirectToRoute('admin/books');
        
    }

    /**
     * @Route("admin/books/delete/{id}", name="book_delete" , methods={"DELETE"})
     */
    public function deleteBook(string $id){
        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

        $sql = "DELETE FROM books WHERE id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $this->redirectToRoute('admin/books');

    }
    
    /**
     * @Route("admin/books/add", name="book_add" , methods={"POST"})
     */
    public function addBook(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

      
		$isbn = $request->get('isbn');
		$title = $request->get('title');
		$category = $request->get('category');
		$author = $request->get('author');
		$publisher = $request->get('publisher');
		$pub_date = $request->get('pub_date');
        $sql = "INSERT INTO books (isbn, category_id, title, author, publisher, publish_date) VALUES ('$isbn', '$category', '$title', '$author', '$publisher', '$pub_date')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $this->redirectToRoute('admin/books');

    }
}
