<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Main\Doyenne;
use App\Entity\Main\Participant;
use App\Entity\Main\Vicariat;
use App\Form\SearchByVicariatType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/filtre')]
class AdminFiltreController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/{filtre}', name: 'admin_filtre_choix', methods: ['GET','POST'])]
    public function choix(Request $request, $filtre): Response
    {
        return match ($filtre){
            'grade' => $this->gradeFiltre($request),
            'vicariat' => $this->vicariatFiltre($request),
            'doyenne' => $this->doyenneFiltre($request),
            'section' => $this->sectionFiltre($request),
            default => $this->defautFiltre($request)
//            default => $this->redirectToRoute('admin_participants_liste',[], Response::HTTP_SEE_OTHER)
        };
    }

    private function defautFiltre($request): Response
    {
        $emMain = $this->doctrine->getManager('default');

        return $this->render('admin/participant_contentieux.html.twig',[
            'participants' => $emMain->getRepository(Participant::class)->getAllByStatusCompletedOrNot(),
//            'vicariats' => $emMain->getRepository(Vicariat::class)->
        ]);
    }

    private function gradeFiltre(Request $request)
    {

    }

    private function vicariatFiltre(Request $request): Response
    {
        $emMain = $this->doctrine->getManager('default');
        $form = $this->createForm(SearchByVicariatType::class, new Doyenne());
        $form->handleRequest($request);

        $participants = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $vicariat = $form->get('vicariat')->getData(); // ← objet Vicariat

            $participants = $emMain->getRepository(Participant::class)
                ->getAllByVicariat($vicariat->getId(), 'complete'); // ← adapter selon votre entité
        } else {
            $participants = $emMain->getRepository(Participant::class)
                ->getAllByStatusCompletedOrNot('complete');
        }

        return $this->render('admin/participant_filtre_vicariats.html.twig',[
            'participants' => $participants,
            'form' => $form
        ]);
    }

    private function doyenneFiltre(Request $request)
    {
    }

    private function sectionFiltre(Request $request)
    {
    }
}
