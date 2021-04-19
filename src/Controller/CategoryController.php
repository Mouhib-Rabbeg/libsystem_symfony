<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;

class CategoryController extends AbstractController
{
  
    /**
     * @Route("admin/category/delete/{id}", methods={"GET","HEAD"} )
     */
    public function delete_cat(Request $request,String $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
            $conn = $entityManager->getConnection(); 
           
    
            $sql = "DELETE FROM category WHERE id = $id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            

            //return $this->redirectToRoute('admin/category');
            return $this->redirect('http://127.0.0.1:8000/admin/category');
    }

    /**
     * @Route("/admin/category" ,methods={"get","HEAD"}  )
     */
    public function index(): Response
    {

            $caregories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'caregories' => $caregories,
        ]);
    }


    /**
     * @Route("admin/category/{id}", name="category_row" , methods={"GET"})
     */
    public function getCategoryRow(string $id): Response{

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 
       

        $sql = "SELECT * FROM category WHERE id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $book = $stmt->fetchAllAssociative(); 
        
        return $this->json(['category' => $book]);
    }


    /**
     * @Route("admin/category/edit", name="category_edit" , methods={"POST"})
     */
    public function editBook(Request $request): Response{
        

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

        $id = $request->get('id');
		$name = $request->get('name');
		
        
        $sql = "UPDATE category SET name = '$name' WHERE id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
       
        return $this->redirectToRoute('admin/category'); 
        
    }


    /**
     * @Route("/admin/category/add" ,methods={"POST","HEAD"}  )
     */
    public function add_cat(): Response
    {

        $request = Request::createFromGlobals();
        $name = $request->request->get('name', 'default category');

        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 
       
        
        $sql = "INSERT INTO category (name) VALUES ('$name')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        

        //return $this->redirectToRoute('admin/category');
        return $this->redirect('http://127.0.0.1:8000/admin/category');
    }

    /**
     * @Route("admin/category/delete/{id}", name="category_delete" , methods={"DELETE"})
     */
    public function deleteBook(string $id){
        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection(); 

        $sql = "DELETE FROM category WHERE id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $this->redirectToRoute('admin/category');

    }
    
}
