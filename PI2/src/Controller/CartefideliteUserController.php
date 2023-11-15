<?php

// src/Controller/CartefideliteUserController.php

namespace App\Controller;

use App\Entity\Cartefidelite;
use App\Repository\CartefideliteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartefideliteUserController extends AbstractController
{  

    #[Route('/user', name: 'app_user')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

 
    #[Route('/user/cartefidelite', name: 'app_cartefidelite_user')]
    public function index(CartefideliteRepository $repo,UserRepository $userRepository): Response
    { $id = 20; 
    $user = $userRepository->find($id); 
    $cartefidelite = $user->getCartefidelite();
        
        return $this->render('cartefideliteUser/index.html.twig', [
            'controller_name' => 'CartefideliteUserController','cartefidelite'=>$cartefidelite,'user'=>$user
        ]);
    }
}
