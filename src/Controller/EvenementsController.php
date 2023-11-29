<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenements;
use App\Form\EvenementsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email; 

use Symfony\Component\Mailer\MailerInterface; 
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\PdfService;
use Symfony\Component\Serializer\SerializerInterface;

class EvenementsController extends AbstractController
{



    private $mailer;

    public function __construct(SerializerInterface $serializer,MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->serializer = $serializer;

    }



    #[Route('/evenements', name: 'app_evenements')]
    public function index(): Response
    {
        return $this->render('evenements/index.html.twig', [
            'controller_name' => 'EvenementsController',
        ]);
    }


    #[Route("/evenements/add", name: 'evenements_add', methods: ['GET', 'POST'])]
    public function addEvenement(Request $request): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingEvent = $this->getDoctrine()->getRepository(Evenements::class)->findOneBy([
                'titre' => $evenement->getTitre(),
                // Ajoutez ici d'autres critères pour vérifier l'unicité de l'événement
            ]);

            if ($existingEvent) {
                $this->addFlash('warning', 'Cet événement existe déjà.');
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($evenement);
                $entityManager->flush();

                // Construire le contenu de l'e-mail avec les données brutes du formulaire
                $emailContent = "Nouvel événement ajouté :\n\n";
                $emailContent .= "Titre : " . $evenement->getTitre() . "\n";
                $emailContent .= "Destination : " . $evenement->getDestination(). "\n";

                // Ajoutez d'autres détails de l'événement selon vos besoins

                // Création de l'e-mail
                $email = (new Email())
                    ->from('tinderrhea2021@gmail.com')
                    ->to('mkanzari001@gmail.com')
                    ->subject('Nouvel événement ajouté')
                    ->text($emailContent); // Utilisation de text() pour spécifier le contenu en texte brut

                // Envoi de l'e-mail via le Mailer
                $this->mailer->send($email);

                return $this->redirectToRoute('evenements_show');
            }
        }

        return $this->render('evenements/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    


    
    #[Route('/evenements/show', name: 'evenements_show',)]
    public function show(EvenementsRepository $evenementsRepository): Response
    {
        $evenements = $evenementsRepository->findAll();

        return $this->render('evenements/show.html.twig', [
            'evenements' => $evenements,
        ]);
    }

#[Route('/evenements/edit/{id}', name: 'evenements_edit',methods: ['GET', 'POST'])]
public function edit(Request $request, int $id): Response
{
    $evenement = $this->getDoctrine()->getRepository(Evenements::class)->find($id);

    if (!$evenement) {
        throw $this->createNotFoundException('L\'événement n\'existe pas');
    }
    $evenement->setImage(null); // Initialise le champ image à null

    $form = $this->createForm(EvenementsType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {


        $newImage = $form->get('image')->getData();

        $newImage = $form->get('image')->getData();
        if ($newImage !== null) {
            $newFilename = uniqid().'.'.$newImage->guessExtension();
            $newImage->move(
                $this->getParameter('image_directory'),
                $newFilename
            );
            // Stocker le chemin de l'image dans l'attribut image de l'entité
            $evenement->setImage($newFilename);
        }
        

        // Enregistrez les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redirigez l'utilisateur vers la page des événements après la modification
        return $this->redirectToRoute('evenements_show');
    }

    // Rendez la vue du formulaire pour la modification avec le formulaire et les données
    return $this->render('evenements/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}




#[Route('/evenements/delete/{id}', name: 'evenements_delete', methods: ['POST'])]
public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
{
    $evenement = $entityManager->getRepository(Evenements::class)->find($id);

    if (!$evenement) {
        throw $this->createNotFoundException('L\'événement n\'existe pas');
    }

    if ($this->isCsrfTokenValid('delete'.$evenement->getIdevenement(), $request->request->get('_token'))) {
        $entityManager->remove($evenement);
        $entityManager->flush();
    }

    return $this->redirectToRoute('evenements_show');
}


#[Route('/evenements/generate-pdf', name: 'evenements_generate_pdf')]
public function generatePDF(EvenementsRepository $evenementsRepository, PdfService $pdfService): Response
{
    // Récupérer tous les événements depuis le repository
    $evenements = $evenementsRepository->findAll();

    // Générer le contenu HTML à partir des événements récupérés
    $htmlContent = $this->renderView('evenements/show.html.twig', [
        'evenements' => $evenements,
    ]);

    // Générer le PDF à partir du contenu HTML
    $pdfContent = $pdfService->generatePdfFromHtml($htmlContent);

    // Renvoyer le PDF en tant que réponse pour le téléchargement
    $response = new Response($pdfContent);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment;filename=evenements.pdf');

    return $response;
}


#[Route('/events', name: 'app_events')]
public function events(EvenementsRepository $evenementsRepository): Response
{
    $lowBudgetEvents = $evenementsRepository->findLowBudgetEvents();

    $data = [];
    foreach ($lowBudgetEvents as $event) {
        $data[$event->getTitre()] = $event->getPrix();
    }

    $closestEvent = $evenementsRepository->findClosestEvent();

    return $this->render('espaceclient/events.html.twig', [
        'data' => $data,
        'closestEvent' => $closestEvent,
    ]);
}



#[Route('/evenements/search', name: 'evenements_search_ajax')]
public function searchEvenementsAjax(Request $request, EvenementsRepository $repository, SerializerInterface $serializer)
{
    $criteria = $request->query->get('criteria');
    $searchValue = $request->query->get('searchValue');

    $evenements = $repository->findByCriteria($criteria, $searchValue);

    // Serialize les événements pour les renvoyer en JSON
    $data = $serializer->serialize($evenements, 'json', ['groups' => 'evenements']);

    return new JsonResponse($data, 200, [], true);
}



}