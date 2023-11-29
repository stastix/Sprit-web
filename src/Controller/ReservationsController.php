<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends AbstractController
{
    #[Route('/reservations', name: 'app_reservations')]
    public function index(): Response
    { 
        return $this->render('reservations/index.html.twig', [
            'controller_name' => 'ReservationsController',
        ]);
    }
    #[Route('/reservations/list', name: 'slist_evennement')]
    public function slist()
    {
        return $this->render('reservations/slist.html.twig');
    }
    #[Route('/reservations/list/booking', name: 'sbooking')]
    public function sbooking(Request $request, ManagerRegistry $manager, CommentsRepository $commentsRepository): Response
    {
        $em = $manager->getManager();

        $Comments = new Comments();
    
        $form = $this->createForm(CommentsType::class, $Comments);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $Comments->setIdEvent(5);
            $Comments->setUserName('saif');
            $em->persist($Comments);
            $em->flush();
            return $this->render('reservations/sbooking.html.twig', [
                'comments' => $commentsRepository->findAll(),
                'f' => $form->createView()]);



        }

        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findAll(),
            'f' => $form->createView()]);
    }


    #[Route('/reservations/edit/{id}', name: 'comment_edit')]
    public function editBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {
        $em = $manager->getManager();

        $comment  = $commentsRepository->find($id);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($comment);
            $em->flush();
            return $this->render('reservations/sbooking.html.twig', [
                'comments' => $commentsRepository->findAll(),
                'f' => $form->createView()]);
        }

        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findAll(),
            'f' => $form->createView()]);
    }

    #[Route('/reservations/delete/{id}', name: 'comment_delete')]
    public function deleteBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {
        $em = $manager->getManager();

        $comment1  = $commentsRepository->find($id);
      
       
    
            $em->remove($comment1);
            $em->flush();
           
        $Comments = new Comments();
            $form = $this->createForm(CommentsType::class, $Comments);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $Comments->setIdEvent(5);
                $Comments->setUserName('saif');
                $em->persist($Comments);
                $em->flush();
                return $this->render('reservations/sbooking.html.twig', [
                    'comments' => $commentsRepository->findAll(),
                    'f' => $form->createView()]);
                }
                return $this->render('reservations/sbooking.html.twig', [
                    'comments' => $commentsRepository->findAll(),
                    'f' => $form->createView()]);
     
    }


}
