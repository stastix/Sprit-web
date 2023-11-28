<?php

namespace App\Controller;
use App\Entity\Ratingsaif;
use App\Repository\EventssaifRepository;
use App\Repository\RatingsaifRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Comments;
use App\Entity\Reservation;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
            'events' => $eventRepository->findAll(),
            'eventsD' => $eventRepository->findAllDistinctdestination(),
            'baw'=> $eventRepository->findAllDistinctTypes(),
        ]);
    }

    #[Route('/reservations/listchoix', name: 'slist_evennement_choix')]
    public function slistchoix(Request $request,EventssaifRepository $eventRepository): Response
    {
        $dest = $request->get('dest');
        $dest2 = $request->get('type');
        return $this->render('reservations/slist.html.twig', [
            'events' => $eventRepository->findBychoix($dest,$dest2)
            ,
            'eventsD' => $eventRepository->findAllDistinctdestination(),
            'baw'=> $eventRepository->findAllDistinctTypes(),
         
        ]);
    }
    #[Route('/reservations/listchoix2', name: 'slist_evennement_choix2')]
    public function slistchoix2(Request $request, EventssaifRepository $eventRepository): Response
    {
        $dest = $request->get('dest');
        $dest2 = $request->get('type');
    
        $events = $eventRepository->findBychoix($dest, $dest2);
    
        $response = new JsonResponse([
            'events' => $events,
        ]);
    
        return $response;
    }
 
    
    #[Route('/reservations/list/booking/{id}', name: 'sbooking')]
    public function addcomment(Request $request, $id, ManagerRegistry $manager, EventssaifRepository $event, CommentsRepository $commentsRepository): Response
    {
        $events = $event->find($id);
        $image = $events->getImagesaif();
        $prix=$events->getPrixsaif();
        $em = $manager->getManager();
    
        $Comments = new Comments();
    
        $form = $this->createForm(CommentsType::class, $Comments);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $Comments->setIdEvent($id);
            $Comments->setEventssaif($events);
            $Comments->setUserName('saif');
          
            $em->persist($Comments);
            $em->flush();
            $this->addFlash('successC', 'Comment ajouté');
            return $this->redirectToRoute('sbooking', ['id' => $id]);
        }
    
        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findByidevent($id),
            'f' => $form->createView(),
            'id' => $id,
            'im' => $image,
            'pr' => $prix
        ]);
    }


    #[Route('/reservations/edit/{id}', name: 'comment_edit')]
    public function editBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {
        $em = $manager->getManager();

        $comment  = $commentsRepository->find($id);
        $f2=$comment->getIdEvent();
        $imm=$comment->getEventssaif();
        $im=$imm->getImagesaif();
        $prix=$imm->getPrixsaif();
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
            , 'pr' => $prix
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
        $basePrice=$fadit->getPrixsaif();
         $em1 = $manager->getManager();
        $reservation = new Reservation();
        $adults = $request->get('nbAdults');
        $kids = $request->get('nbKids');
       
        
        $reservation->setNbAdults($adults);
        $reservation->setNbkids($kids);
        $reservation->setEventId($id);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nbAdults = isset($_POST['nbAdults']) ? (int)$_POST['nbAdults'] : 0;
            $nbKids = isset($_POST['nbKids']) ? (int)$_POST['nbKids'] : 0;
           
            $discountForKids = 0.3 * $basePrice * $nbKids;  
            $totalSeats = $nbAdults + $nbKids;
            $additionalSeatsDiscount = 0;
        
            if ($totalSeats > 2) {
                for ($i = 3; $i <= $totalSeats; $i++) {
                    $additionalSeatsDiscount += 0.05 * $basePrice;
                }
            }
        
            $newPrice = $basePrice * $totalSeats - $discountForKids - $additionalSeatsDiscount;
             $reservation->setPrixR($newPrice);
        }
        else {
        $reservation->setPrixR($basePrice);
        }
        $reservation->setUserId(2);
      
        $em1->persist($reservation);
        $em1->flush();
        $this->addFlash('successR', 'Réservation effectuée avec succès!');
        return $this->redirectToRoute('sbooking', ['id'=> $id]);
        
 
}






#[Route('/reservations/update_rating/{id}', name: 'update_event_rating')]
public function updateEventRating(Request $request, ManagerRegistry $manager,EventssaifRepository $eventssaifRepository, $id,RatingsaifRepository $r ): JsonResponse
{
    $usrid=1;
    $test=$r->findRating($usrid,$id);
    if ($test === null) {
    $em = $manager->getManager();
    $event = $eventssaifRepository->find($id);

    $ur = $request->get('userRating');
    $RS= new Ratingsaif();
    $RS->setIdUser($usrid);
    $RS->setValueRaiting($ur);
    $RS->setEventR( $event);
    $em->persist($RS);
    $em->flush();
    $old=$event->getRating();
    $nbRaiting=$event->getEntityCountOfRaiting();
    $new=(($old * $nbRaiting)+$ur)/($nbRaiting+1);
   $event->setRating($new);
   $em->persist($event);
    $em->flush();



    return new JsonResponse(['message' => 'Rating updated successfully']);}
    return new JsonResponse(['message'=> 'tu est deja donner ton raiting']);
}



} 
