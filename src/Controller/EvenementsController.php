<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenements;
use App\Form\EvenementsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;




class EvenementsController extends AbstractController
{
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

                $this->addFlash('success', 'L\'événement a été ajouté avec succès.');
                // Redirect after successful addition
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


}
