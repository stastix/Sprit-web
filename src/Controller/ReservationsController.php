<?php

namespace App\Controller;
use App\Repository\EventssaifRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Comments;
use App\Entity\Reservation;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use App\Repository\ReservationRepository;
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
    public function slist(EventssaifRepository $eventRepository): Response
    {
        return $this->render('reservations/slist.html.twig', [
            'events' => $eventRepository->findAll()]);
    }
    #[Route('/reservations/list/booking/{id}', name: 'sbooking')]
    public function sbooking(Request $request, $id,ManagerRegistry $manager, EventssaifRepository $event,CommentsRepository $commentsRepository): Response
    {
        $events= $event->find($id);
        $image=$events->getImagesaif();
        $em = $manager->getManager();

        $Comments = new Comments();
    
        $form = $this->createForm(CommentsType::class, $Comments);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $Comments->setIdEvent($id);
            $Comments->setEventssaif($events);
            $Comments->setUserName('saif');
            $em->persist($Comments);
            $em->flush();
            $this->addFlash('successC', 'Comment ajouter');
            return $this->redirectToRoute('sbooking', ['id'=> $id]);



        }

        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findByidevent($id),
            'f' => $form->createView()
            ,'id'=>$id,
           'im'=>$image ]
        );
    }


    #[Route('/reservations/edit/{id}', name: 'comment_edit')]
    public function editBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {
        $em = $manager->getManager();

        $comment  = $commentsRepository->find($id);
        $f2=$comment->getIdEvent();
        $imm=$comment->getEventssaif();
        $im=$imm->getImagesaif();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('successC', 'Comment modifier');
            return $this->redirectToRoute('sbooking', ['id'=> $f2]);
        }

        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findByidevent($f2),
            'f' => $form->createView()
            ,'id'=>$f2
            ,'im'=>$im
            ]);
    }

    #[Route('/reservations/delete/{id}', name: 'comment_delete')]
    public function deleteBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {
        $em = $manager->getManager();
        
        $Comment1 = $commentsRepository->find($id);
        $imm=$Comment1->getEventssaif();
        $im=$imm->getImagesaif();
        $f=$Comment1->getIdEvent();
        $em->remove($Comment1);
        $em->flush();
        $this->addFlash('successC', 'Comment suprimer');
                return $this->redirectToRoute('sbooking', ['id'=> $f]);
     
    }
    #[Route('/reservations/add/{id}', name: 'add_reservation')]
    public function addreservation(Request $request,$id, ManagerRegistry $manager,EventssaifRepository $eventssaifRepository  , CommentsRepository $commentsRepository): Response
    {


        
        $fadit=$eventssaifRepository->find($id);
        $im=$fadit->getImagesaif();
         $em1 = $manager->getManager();
        $reservation = new Reservation();
        $adults = $request->get('nbAdults');
        $kids = $request->get('nbKids');
       
        
        $reservation->setNbAdults($adults);
        $reservation->setNbkids($kids);
        $reservation->setEventId($id);
        $reservation->setUserId(2);
      
        $em1->persist($reservation);
        $em1->flush();
        $this->addFlash('successR', 'Réservation effectuée avec succès!');
        return $this->redirectToRoute('sbooking', ['id'=> $id]);
        
 
}







} 
