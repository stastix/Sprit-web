<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\UserRepository;


class DemandeController extends AbstractController
{
    #[Route('/demande', name: 'add_demande')]
    public function index(Request $request, ManagerRegistry $managerRegistry,DemandeRepository $demandeRepository,UserRepository $userRepository): Response
    {
        $user=$userRepository->find(2);
        $em = $managerRegistry->getManager();
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
            $form->handleRequest($request);
            if ($form->isSubmitted()&& $form->isValid()) { 

                $duration = $form->get('duration')->getData();

                if ($duration > 10) {
               
                    $this->addFlash('successC', 'Duration must not exceed 10 days.');
                } else {
                $demande->setUseName($user); 

                $em->persist($demande);
                $em->flush();
                $this->addFlash('successC', 'request added');
                return $this->redirectToRoute('add_demande');}
            }

        return $this->render('demande/index.html.twig', [
          'demands' => $demandeRepository->findAll(),
          'f' => $form->createView()
        ]);
    }


    #[Route('/demande/edit/{id}', name: 'demande_edit')]
    public function editDemande(Request $request, ManagerRegistry $manager, DemandeRepository $demandeRepository, $id): Response
    {
        $em = $manager->getManager();
        $demande  = $demandeRepository->find($id);
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($demande);
            $em->flush();
            $this->addFlash('successC', 'request edited');

            return $this->redirectToRoute('add_demande');
        }
            return $this->render('demande/index.html.twig', [
                'demands' => $demandeRepository->findAll(),
                'f' => $form->createView()]);
    
    }


    #[Route('/demande/delete/{id}', name: 'demande_delete')]
    public function deleteDemande (Request $request, ManagerRegistry $manager, $id, DemandeRepository $demandeRepository ): Response
    {
        $em = $manager->getManager();
        $demande = $demandeRepository->find($id);
        if (!$demande) {
            
             return $this->redirectToRoute('add_demande');
            
        }
        $em->remove($demande);
        $em->flush();
        $this->addFlash('successC', 'request deleted');
        
            return $this->redirectToRoute('add_demande');
    
    }

    #[Route('/showdemande', name:'demande_show')]
    public function show(DemandeRepository  $demandeRepository): Response
    {

        return $this->render('demande/showd.html.twig', [
            'demands' =>$demandeRepository->showrdemandeUser(),
        ]);}  
        
        
       


}
