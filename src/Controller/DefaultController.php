<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Main\ClotureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function __construct(private ClotureRepository $clotureRepository)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->clotureRepository->findBy(['isActif' => true],['id' => 'DESC']))
        {
            return $this->redirectToRoute('app_cloture');
        }
        return $this->render('frontend/home.html.twig');
    }

    #[Route('/choix', name: "app_profil")]
    public function profil()
    {
        if ($this->clotureRepository->findBy(['isActif' => true],['id' => 'DESC']))
        {
            return $this->redirectToRoute('app_cloture');
        }

        return $this->render('frontend/profil.html.twig');
    }

    #[Route('/app/cloture', name: 'app_cloture')]
    public function cloture()
    {
        if ($this->clotureRepository->findBy(['isActif' => false], ['id' => 'DESC'])){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('frontend/cloture.html.twig');
    }
}
