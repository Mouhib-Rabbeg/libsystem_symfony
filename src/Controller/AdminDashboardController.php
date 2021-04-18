<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Admin;
use App\Entity\Books;
use App\Entity\Students;


use Symfony\Component\HttpFoundation\RequestStack;
class AdminDashboardController extends AbstractController
{
    private $request;
    public function __construct(RequestStack $request){

        $this->request = $request;
    }

    
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(): Response
    {
        $year = $this->request->getCurrentRequest()->query->get('year');

        $books = $this->getDoctrine()
            ->getRepository(Books::class)
            ->findAll();
        $students = $this->getDoctrine()
            ->getRepository(Students::class)
            ->findAll();

            $today = date('Y-m-d');
            
           $entityManager = $this->getDoctrine()->getManager();
            $conn = $entityManager->getConnection(); 
           
    
            $sql = "SELECT * FROM returns WHERE date_return = '$today'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $returns = $stmt->fetchAllAssociative(); 

            $sql = "SELECT * FROM borrow WHERE date_borrow = '$today'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $borrows = $stmt->fetchAllAssociative(); 



            //char config 
            $and = 'AND YEAR(date) = '.$year;
            $months = array();
            $return = array();
            $borrow = array();
            for( $m = 1; $m <= 12; $m++ ) {
                $sql = "SELECT * FROM returns WHERE MONTH(date_return) = '$m' AND YEAR(date_return) = '$year'";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $rquery = $stmt->fetchAllAssociative(); 
                array_push($return, count($rquery));

                $sql = "SELECT * FROM borrow WHERE MONTH(date_borrow) = '$m' AND YEAR(date_borrow) = '$year'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $bquery = $stmt->fetchAllAssociative(); 
                array_push($borrow, count($bquery));

                
            }

           
            $return = json_encode($return);
            $borrow = json_encode($borrow);

            
             
        return $this->render('admin_dashboard/index.html.twig', [
            'year'=> $year,
            'books_quantity' => count($books),
            'students_quantity' => count($students),
            'returns' => count($returns),
            'borrow' => count($borrows),
            
            'return_chart' => $return,
            'borrow_chart' => $borrow,
        ]);
    }
}
