<?php

namespace App\Controller;

use App\Entity\Reclamation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }


    #[Route('/reclamation/add', name: 'add_reclamation', methods: ['GET', 'POST'])]
    public function addReclamation(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $em = $managerRegistry->getManager();
    
        // Retrieve form data
        $cibleReclamation = $request->request->get('CibleReclamation');
        $email = $request->request->get('email');
        $date = new \DateTime($request->request->get('date'));
        $message = $request->request->get('message');
    
      
        $reclamation = new Reclamation();
    
   
        $reclamation->setDateReclamation($date);
    
      
        if ($cibleReclamation !== null) {
            $reclamation->setCibleReclamation($cibleReclamation);
        }
    
        if ($email !== null) {
            $reclamation->setEmail($email);
        }
    
        // Check and set text_reclamation
        if ($message !== null && trim($message) !== '') {
            $reclamation->setTextReclamation($message);
        } else {
  
            $reclamation->setTextReclamation('Default Text'); // Replace 'Default Text' with an appropriate default value
        }
    
        $em->persist($reclamation);
        $em->flush();
    
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

 
}