<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class LogoutController extends AbstractController
{
    /**
     * @Route("/logout", name="logout")
     */
    public function index(Request $request): Response
    {
        
         $this->get('session')->set('student','');;
        
        return $this->redirectToRoute('index');
    }
}
