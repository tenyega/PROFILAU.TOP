<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(JobOfferRepository $jbr, UserRepository $ur): Response
    {
        $user = $ur->findOneById($this->getUser()->getId());
        $jobs = $jbr->findBy(['app_user' => $user], ['createdAt' => 'DESC'], 4);


        return $this->render('dashboard/index.html.twig', [
            'jobs' => $jobs
        ]);
    }
}
