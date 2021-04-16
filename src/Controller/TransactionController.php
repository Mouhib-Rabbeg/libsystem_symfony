<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\RequestStack;

class TransactionController extends AbstractController
{
    private $request;
    public function __construct(RequestStack $request){

        $this->request = $request;
    }
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(): Response
    {
        $action = $this->request->getCurrentRequest()->query->get('action');

        //check if user is connected !
        $userid = '';
        if($this->get('session')->get('student')){
            $userid = $this->get('session')->get('student')->getId();
        }else {
            return $this->redirectToRoute('index');
        }
        
        /* $entityManager = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT * FROM borrow LEFT JOIN books ON books.id=borrow.book_id WHERE student_id = ? ORDER BY date_borrow DESC";
        $RAW_QUERY->setParameter(1,$userid);
        $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $books = $statement->fetchAll(); */
        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection();
        $sql = '';
        
        if($action == "return"){
            $sql = '
            SELECT * FROM returns LEFT JOIN books ON books.id=returns.book_id WHERE student_id = :id ORDER BY date_return DESC
            ';
        }else{
            $sql = '
        SELECT * FROM borrow LEFT JOIN books ON books.id=borrow.book_id WHERE student_id = :id ORDER BY date_borrow DESC
            ';
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $userid]);
        $books = $stmt->fetchAllAssociative();


        return $this->render('transaction/index.html.twig', [
            'books' => $books,
            'action'=> $action
        ]);
    }
}
