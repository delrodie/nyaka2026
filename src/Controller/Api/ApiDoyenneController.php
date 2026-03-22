<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\List\Doyenne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/doyenne')]
class ApiDoyenneController extends AbstractController
{
    #[Route('/', name: "api_doyenne_list", methods: ['GET'])]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager('list_manager');
        $doyennes = $em->getRepository(Doyenne::class)->findAll();

        // On formate les données pour éviter les erreurs de circularité ou les objets complexes
        $data = array_map(function (Doyenne $d) {
            return [
                'id' => $d->getId(),
                'nom' => $d->getNom(),
            ];
        }, $doyennes);

        return $this->json($data);
    }
}
