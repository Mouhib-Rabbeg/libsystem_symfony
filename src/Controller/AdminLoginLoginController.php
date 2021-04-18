<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Admin;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
class AdminLoginLoginController extends AbstractController
{
    /**
     * @Route("/admin/login/login", name="admin_login_login")
     */
    public function index(): Response
    {

        $request = Request::createFromGlobals();
        $username = $request->request->get('username', 'default category');
        $password = $request->request->get('password', 'default category');
        

        $admin = $this->getDoctrine()
            ->getRepository(Admin::class)
            ->findOneBy([
                'username' => $username,
               
            ]);
        if($admin){
            if(password_verify($password, $admin->getPassword())){
				$this->get('session')->set('admin', $admin);
                $this->get('session')->set('admin-error', '');
                return $this->redirectToRoute('admin/dashboard');
			} else {
                
                $this->get('session')->set('admin-error', 'check credentials');
                return $this->redirectToRoute('admin/login');
            }  
        } else {
            
            $this->get('session')->set('admin-error', 'check credentials');
            return $this->redirectToRoute('admin/login');
        }
                    
        
    }
}
