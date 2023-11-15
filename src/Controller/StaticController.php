<?php

namespace App\Controller;
use App\Repository\CommentsRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReservationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    #[Route('/static', name: 'app_static')]
    public function index(ReservationRepository $ReservationRepository,CommentsRepository $commentsRepository): Response
    {
        return $this->render('admin/static.html.twig', [
            'reservations' => $ReservationRepository->findAll(),
            'commentaire' => $commentsRepository->findAll(),
            ]);
    }
}
