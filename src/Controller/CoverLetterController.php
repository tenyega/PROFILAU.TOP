<?php

namespace App\Controller;

use Gemini;
use App\Entity\CoverLetter;
use App\Repository\CoverLetterRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CoverLetterController extends AbstractController
{
    #[Route('/cover-letter/generate', name: 'app_cover_letter_generate', methods: ['POST', 'GET'])]
    public function generate(EntityManagerInterface $em, JobOfferRepository $jobs): Response
    {
        $job = $jobs->findOneById($_GET['id']);
        $user = $job->getAppUser();

        if ($user === $this->getUser()) {
            $company = $job->getCompany();
            $title = $job->getTitle();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();

            //GESTION DE GEMINI AI
            $yourApiKey = $this->getParameter('GEMINI_API_KEY');
            $client = Gemini::client($yourApiKey);
            $result = $client->geminiPro()->generateContent(
                "Je veux une lettre de motivation pour la compagnie " . $company . " pour le poste de " . $title . ". 
                Voici les informations pour aider la création de la lettre de motivation :
                Mon nom est " . $lastName . ' et mon prénom est ' . $firstName . "
                Je voudrais qu'à chaque saut de ligne tu me rajoutes la balise html <br>."
            );

            $cl = new CoverLetter;
            $cl
                ->setContent($result->text())
                ->setAppUser($user)
                ->setJobOffer($job);
            $em->persist($cl);
            $em->flush();

            return $this->redirectToRoute('app_cover_letter_show', ['coverLetter' => $cl, 'id' => $cl->getId()]);
        }
        return $this->redirectToRoute('app_home');
    }
    #[Route('/cover-letter/{id}', name: 'app_cover_letter_show', methods: 'GET')]
    public function show(int $id, CoverLetterRepository $cls): Response
    {
        $cl = $cls->findOneById($id);


        return $this->render('cover_letter/show.html.twig', ['jobOfferName' => $cl->getJobOffer()->getTitle(), 'CLContent' => $cl->getContent()]);
    }

    #[Route('/cover-letter/{id}/delete', name: 'app_cover_letter_delete', methods: ['GET', 'POST'])]
    public function delete(int $id, EntityManagerInterface $em, CoverLetterRepository $letters): Response
    {
        $letter = $letters->findOneById($id);

        if ($letter->getAppUser() !== $this->getUser()) {

            $this->addFlash('error', 'You are not authorized to edit this cover letter');
            return $this->redirectToRoute('app_home');
        }

        $em->remove($letter);
        $em->flush();

        $this->addFlash('success', 'Your cover letter has been updated');
        return $this->redirectToRoute('app_dashboard');
    }
}
