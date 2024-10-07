<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiJobOfferController extends AbstractController
{
    #[Route('/api/job-offers/update-status', name: 'app_kanban_updateStatus', methods: ['POST'])]
    public function updateStatus(Request $request, EntityManagerInterface $em, JobOfferRepository $jor)
    {

        $data = json_decode($request->getContent(), true);

        // Validate data
        if (!isset($data['id']) || !isset($data['status'])) {
            return new JsonResponse(['error' => 'Invalid input'], 400);
        }

        try {
            // Assume you have a Job entity
            $job = $jor->find($data['id']);
            if (!$job) {
                return new JsonResponse(['error' => 'Job not found'], 404);
            }

            // Update the job status
            switch ($data['status']) {
                case 'A postuler':
                    $status = "A postuler";
                    break;
                case 'En attente':
                    $status = "En attente";
                    break;
                case 'Entretien':
                    $status = "Entretien";
                    break;
                case 'Refusé':
                    $status = "Refusé";
                    break;
                case 'Accepté':
                    $status = "Accepté";
                    break;
                default:

                    break;
            }
            $job->setStatus($status);
            $em->persist($job);
            $em->flush();

            return new JsonResponse(['status' => 'success']); // Return a success response
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500); // Log the exception message
        }
    }



    #[Route('/ask', name: 'app_api_job', methods: ['GET'])]
    public function ask(): Response
    {
        return $this->render('ask.html.twig');
    }
}
