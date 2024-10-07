<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class JobOfferController extends AbstractController
{
    #[Route('/job-offers', name: 'app_job_offer_all', methods: 'GET')]
    public function all(JobOfferRepository $jor): Response
    {
        $jobs = $jor->findBy(['app_user' => $this->getUser()]);

        return $this->render('job_offer/list.html.twig', ['jobs' => $jobs]);
    }
    #[Route('/job-offers/{id}', name: 'app_job_offer_show', methods: 'GET')]
    public function show(int $id, JobOfferRepository $jobs): Response
    {
        $job = $jobs->findOneById($id);
        return $this->render('job_offer/show.html.twig', ['job' => $job]);
    }
    #[Route('/job-offers/{id}/edit', name: 'app_job_offer_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, JobOfferRepository $jobs, EntityManagerInterface $em): Response
    {
        $job = $jobs->findOneById($id);

        if ($job->getAppUser() !== $this->getUser()) {
            $this->addFlash('error', 'You are not authorized to edit this note');
            return $this->redirectToRoute('app_job_offer_all');
        }

        $form = $this->createForm(JobOfferType::class, $job);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            $this->addFlash('success', 'Your note has been updated');
            return $this->redirectToRoute('app_job_offer_show', ['id' => $job->getId()]);
        }

        return $this->render('job_offer/edit.html.twig', ['formJobOffer' => $form]);
    }
    #[Route('/job-offers/{id}/delete', name: 'app_job_offer_delete', methods: ['GET', 'POST'])]
    public function delete(int $id, EntityManagerInterface $em, JobOfferRepository $jobs): Response
    {
        $job = $jobs->findOneById($id);

        if ($job->getAppUser() !== $this->getUser()) {

            $this->addFlash('error', 'You are not authorized to edit this note');
            return $this->redirectToRoute('app_job_offer_all');
        }

        $em->remove($job);
        $em->flush();

        $this->addFlash('success', 'Your note has been updated');
        return $this->redirectToRoute('app_dashboard');
    }
    #[Route('/job-offer/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $job = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setAppUser($this->getUser());
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('app_job_offer_show', ['id' => $job->getId()]);
        }

        return $this->render('job_offer/new.html.twig', ['formJobOffer' => $form]);
    }

    #[Route('/api/job-offers/update-status/{id}', name: 'app_job_offer_status', methods: ['POST'])]
    public function changeStatus(int $id, Request $request, EntityManagerInterface $em,  JobOfferRepository $jr): Response
    {
        $job = $jr->findOneById($id);
        if ($job->getAppUser() == $this->getUser()) {
            $status = $request->request->get('status');
            switch ($status) {
                case 0:
                    $job->setStatus('A postuler');
                    break;
                case 1:
                    $job->setStatus('En attente');
                    break;
                case 2:
                    $job->setStatus('Entretien');
                    break;
                case 3:
                    $job->setStatus('Refusé');
                    break;
                case 4:
                    $job->setStatus('Accepté');
                    break;
            }

            $em->persist($job);
            $em->flush();
            $url = $request->headers->get('referer');

            return $this->redirect($url);
        }
        return $this->redirectToRoute('app_home');
    }
}
