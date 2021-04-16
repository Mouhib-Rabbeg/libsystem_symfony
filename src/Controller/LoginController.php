<?php

namespace App\Controller;
use App\Entity\Students;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
class LoginController extends AbstractController
{
    private $request;
   
    
    
    /**
     * @Route("/login", name="login")
     */
    public function index(): Response
    {
        $request = Request::createFromGlobals();
        //$this->get('session')->set('name','wiouw');
        $studentid = $request->request->get('student', 'default category');
        $user = $this->getDoctrine()
            ->getRepository(Students::class)
            ->findOneBy([
                'studentId' => $studentid,
               
            ]);
               
        
            if (!$user) {
                $this->get('session')->set('error', "no student found");

            }else{
                $this->get('session')->set('student', $user);
                $this->get('session')->set('error', '');

            }
        return $this->redirectToRoute('index');
        
    }
}
