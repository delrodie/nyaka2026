<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Main\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/participants')]
class AdminParticipantController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/', name:'admin_participants_liste')]
    public function liste(): Response
    {
        $emMain = $this->doctrine->getManager('default');

        return $this->render('admin/participant_list.html.twig',[
            'participants' => $emMain->getRepository(Participant::class)->getAllByStatusCompletedOrNot('complete')
        ]);
    }

    #[Route('/non-finalisees', name:'admin_participants_nonfinalisees')]
    public function nonfinalisees(): Response
    {
        $emMain = $this->doctrine->getManager('default');

        return $this->render('admin/participant_contentieux.html.twig',[
            'participants' => $emMain->getRepository(Participant::class)->getAllByStatusCompletedOrNot()
        ]);
    }
}
