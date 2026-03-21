<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('frontend/home.html.twig');
    }

    #[Route('/choix', name: "app_profil")]
    public function profil()
    {
        return $this->render('frontend/profil.html.twig');
    }
}
