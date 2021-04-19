<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\HttpFoundation\Request;
class TransactionReturnController extends AbstractController
{
    /**
     * @Route("/admin/transaction/return", name="transaction_return")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

        $sql = "SELECT *, students.student_id AS stud FROM returns LEFT JOIN students ON students.id=returns.student_id LEFT JOIN books ON books.id=returns.book_id ORDER BY date_return DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $return = $stmt->fetchAllAssociative(); 
        return $this->render('transaction_return/index.html.twig', [
            'borrowed_books' => $return,
        ]);
    }



    /**
     * @Route("/admin/transaction/return/add", name="transaction_return_add",methods={"POST"})
     */
    public function addReturn(Request $request){

        $isbn = $request->get('isbn');
		$student = $request->get('student');

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection();
        //check student exist
         $sql = "SELECT * FROM students WHERE student_id = '$student'";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $res_student = $stmt->fetchAllAssociative();
        
         $sql_isbn = "SELECT * FROM books WHERE isbn = '$isbn'";
         $stmt = $conn->prepare($sql_isbn);
         $stmt->execute();
         $res_isbn = $stmt->fetchAllAssociative();

         //check if user exist in borrow table

            $id = $res_isbn[0]['id'];
            $id_student = $res_student[0]['id'];

         $sql_return = "SELECT * FROM borrow WHERE student_id = '$id_student' AND book_id = '$id' AND status = 0";
         $stmt = $conn->prepare($sql_return);
         $stmt->execute();
         $res_return = $stmt->fetchAllAssociative();
         
         

         if(count($res_return)<1){
            $this->get('session')->set('transaction-return_error', 'check inputs ');
            return $this->redirectToRoute('admin/transaction/return');
         }
         else{

            $this->get('session')->set('transaction-student_error', '');
            $this->get('session')->set('transaction-isbn_error', '');
            $id = $res_isbn[0]['id'];
            $id_student = $res_student[0]['id'];
            $sql = "INSERT INTO returns (student_id, book_id, date_return) VALUES ('$id_student', '$id', NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $sql = "UPDATE books SET status = 0 WHERE id = '$id'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $borrow_id = $res_return[0]['id'];
            $sql = "UPDATE borrow SET status = 1 WHERE id = '$borrow_id'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $this->redirectToRoute('admin/transaction/return');
            
        }
        

    }
}
