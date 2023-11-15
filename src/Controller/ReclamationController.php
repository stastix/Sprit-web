<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationsType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
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


 #[Route('/reclamation/add', name: 'add_reclamation')]
public function addReclamation(Request $request, ManagerRegistry $managerRegistry, ReclamationRepository $reclamationRepository,UserRepository $userRepository): Response
{
    $user=$userRepository->find(2);
    $em = $managerRegistry->getManager();
    $reclamation = new Reclamation();
    
    $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) { 
            
            
            $reclamation->setUseName($user);  

        $em->persist($reclamation);
        $em->flush();
        $this->addFlash('successC', 'complaint added');
        return $this->redirectToRoute('add_reclamation');
    }

    return $this->render('reclamation/index.html.twig',[
    'reclamations' => $reclamationRepository->findAll(),
                'f' => $form->createView()]); // Redirect to the desired route after form submission
}


    #[Route('/reclamation/edit/{id}', name: 'reclamation_edit')]
    public function editReclamation(Request $request, ManagerRegistry $manager, ReclamationRepository $reclamationRepository, $id): Response
    {
        $em = $manager->getManager();
        $reclamation  = $reclamationRepository->find($id);
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('successC', 'complaint edited');

            return $this->redirectToRoute('add_reclamation');
        }
            return $this->render('reclamation/index.html.twig', [
                'reclamations' => $reclamationRepository->findAll(),
                'f' => $form->createView()]);
    
    }


    #[Route('/reclamation/delete/{id}', name: 'reclamation_delete')]
    public function deleteReclamation (Request $request, ManagerRegistry $manager, $id, ReclamationRepository $reclamationRepository ): Response
    {
        $em = $manager->getManager();
        $reclamation = $reclamationRepository->find($id);
        if (!$reclamation) {
            
             return $this->redirectToRoute('add_reclamation');
            
        }
        $em->remove($reclamation);
        $em->flush();
        $this->addFlash('successC', 'complaint deleted');
        
            return $this->redirectToRoute('add_reclamation');
    
    }

    #[Route('/showreclamation', name:'reclamation_show')]
    public function show(ReclamationRepository  $reclamationRepository): Response
    {



        return $this->render('reclamation/show.html.twig', [
            'reclamations' =>$reclamationRepository->showreclamationandUser(),
        ]);}
    

        #[Route('/editReclamation/{id}', name:'reclamation_mark')]
        public function change( ManagerRegistry $manager, $id, ReclamationRepository  $reclamationRepository): Response
        {
            $em = $manager->getManager();
            $reclamation  = $reclamationRepository->find($id);
            $reclamation ->setEtatReclamation("Read");
            $em->persist($reclamation);
            $em->flush();
           
    
            return $this->render('reclamation/show.html.twig',[
                'reclamations' =>$reclamationRepository->showreclamationandUser(),
            ]);}  


 
}