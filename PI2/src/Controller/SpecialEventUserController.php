<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialEventUserController extends AbstractController
{

    #[Route('/user/specialevent', name: 'app_specialEvent', methods: ['GET'])]
    public function index(\App\Repository\OffrespecialevenmentRepository $offrespecialevenmentRepository): Response
    {
        return $this->render('special_event_user/index.html.twig', [
        'offrespecialevenments' => $offrespecialevenmentRepository->findAll(),
        ]);
    }

}
