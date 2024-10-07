<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class KanbanController extends AbstractController
{
    #[Route('/kanban', name: 'app_kanban', methods: ['GET'])]
    public function index(JobOfferRepository $jor): Response
    {
        $user = $this->getUser();
        $jobOfferEnAttentes = $jor->findBy((['app_user' => $user, 'status' => 'En Attente']));
        $jobOfferAPostulers = $jor->findBy((['app_user' => $user, 'status' => 'A Postuler']));
        $jobOfferEntretiens = $jor->findBy((['app_user' => $user, 'status' => 'Entretien']));
        $jobOfferAcceptes = $jor->findBy((['app_user' => $user, 'status' => 'Accepté']));
        $jobOfferRefuses = $jor->findBy((['app_user' => $user, 'status' => 'Refusé']));

        return $this->render('kanban/index.html.twig', [
            'jobOfferEnAttentes' => $jobOfferEnAttentes,
            'jobOfferAPostulers' => $jobOfferAPostulers,
            'jobOfferEntretiens' => $jobOfferEntretiens,
            'jobOfferAcceptes' => $jobOfferAcceptes,
            'jobOfferRefuses' => $jobOfferRefuses

        ]);
    }
}
