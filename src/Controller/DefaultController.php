<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/signin', name: 'sign_in')]
    public function signin(): Response
    {
        return $this->render('signin.html.twig', [
            'controller_name' => 'DefaultController', ]);
        }
       
        #[Route('/signup', name: 'sign_up')]
        public function signup(): Response
        {
            return $this->render('admin/dashboard.html.twig', [
                'controller_name' => 'DefaultController', ]);
            }

        


           









    

}
