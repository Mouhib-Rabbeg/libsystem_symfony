<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\HttpFoundation\Request;
class TransactionBorrowController extends AbstractController
{
    /**
     * @Route("/admin/transaction/borrow", name="transaction_borrow")
     */
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

        $sql = "SELECT *, students.student_id AS stud, borrow.status AS barstat FROM borrow LEFT JOIN students ON students.id=borrow.student_id LEFT JOIN books ON books.id=borrow.book_id ORDER BY date_borrow DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $borrow = $stmt->fetchAllAssociative(); 

        return $this->render('transaction_borrow/index.html.twig', [
            'borrowed_books' => $borrow,
        ]);
    }
     /**
     * @Route("/admin/transaction/borrow/add", name="transaction_borrow_add",methods={"POST"})
     */
    public function addBorrow(Request $request){

        $isbn = $request->get('isbn');
		$student = $request->get('student');

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection();
        //check student exist
         $sql = "SELECT * FROM students WHERE student_id = '$student'";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $res_student = $stmt->fetchAllAssociative();
        
         $sql_isbn = "SELECT * FROM books WHERE isbn = '$isbn' AND status != 1";
         $stmt = $conn->prepare($sql_isbn);
         $stmt->execute();
         $res_isbn = $stmt->fetchAllAssociative();
         if(count($res_student)<1){
            $this->get('session')->set('transaction-student_error', 'student not found');
            return $this->redirectToRoute('admin/transaction/borrow');
         }
         else if(count($res_isbn)<1){
            $this->get('session')->set('transaction-isbn_error', 'book not found or not available');
            return $this->redirectToRoute('admin/transaction/borrow');
         }else{
            $this->get('session')->set('transaction-student_error', '');
            $this->get('session')->set('transaction-isbn_error', '');
            $id = $res_isbn[0]['id'];
            $id_student = $res_student[0]['id'];
            $sql = "INSERT INTO borrow (student_id, book_id, date_borrow) VALUES ('$id_student', '$id', NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $this->redirectToRoute('admin/transaction/borrow');
            
        }
        

    }
}
