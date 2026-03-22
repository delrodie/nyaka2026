<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\List\Adhesion;
use App\Entity\Main\Grade as GradeMain;
use App\Entity\Main\Participant;
use App\Entity\Main\Section as SectionMain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participant')]
class ParticipantController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/{id}', name:'app_participant_save', methods: ['GET','POST'])]
    public function save(Request $request, Adhesion $adhesion ): Response
    {
        $emMain = $this->doctrine->getManager('default');
        $emList = $this->doctrine->getManager('list_manager');

        $reqProfil = $request->request->get('profil');
        $reqTailleBenjamin = $request->request->get('tailleBenjamin');
        $reqTailleAine = $request->request->get('tailleAine');
        $reqTailleAA = $request->request->get('tailleAA');
        $reqTailleAP = $request->request->get('tailleAP');
        $reqTraitement = $request->request->get('traitement');
        $reqDeclarantNom = $request->request->get('declarantNom');
        $reqDeclarantContact = $request->request->get('declarantContact');
        $reqParticipant = $request->request->get('participant');
        $reqMontant = $request->request->get('montant');
        $reqProfil = $request->request->get('profil');

        $taille = $reqTailleBenjamin ?? $reqTailleAine ?? $reqTailleAA ?? $reqTailleAP ?? "ND";

        $section = $emMain->getRepository(SectionMain::class)->findOneBy([
            'uuid' => $adhesion->getSection()->getId(),
        ]);

        $grade = $emMain->getRepository(GradeMain::class)->findOneBy([
            'uuid' => $adhesion->getGrade()->getId(),
        ]);

        // Sauvegarde des données
        $participant = new Participant();
        $participant->setSection($section);
        $participant->setGrade($grade);
        $participant->setSlug($adhesion->getId());
        $participant->setNomPrenoms($adhesion->getNomPrenoms());
        $participant->setTaille($taille);
        $participant->setTraitement($reqTraitement);
        $participant->setDeclarantNom($reqDeclarantNom);
        $participant->setDeclarantContact($reqDeclarantContact);
        $participant->setMontant($reqMontant);
        $participant->setProfil($reqProfil);

        return $this->render('participant/index.html.twig');
    }
}
