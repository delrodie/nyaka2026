<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/backend', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
         return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CV-AV');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section();
        yield MenuItem::section('MODULE'); // <i class="fa-light fa-hand-holding-dollar"></i>
        yield MenuItem::linkTo(ActiviteCrudController::class, 'Activités', 'fa-solid fa-cubes')
                ->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkTo(ParticipationCrudController::class, 'Participations', 'fa-solid fa-hand-holding-dollar')
                ->setPermission('ROLE_ADMIN');
        yield MenuItem::subMenu('Participants', 'fa-solid fa-users')->setSubItems([
            MenuItem::linkToRoute("Liste des participants", 'fa-solid fa-list', 'admin_participants_liste'),
            MenuItem::linkToRoute("Contentieux", 'fa-solid fa-ban', 'admin_participants_nonfinalisees'),
            MenuItem::linkToRoute("Par Vicariats", 'fa-solid fa-church', 'admin_filtre_choix', ['filtre' => 'vicariat']),
            MenuItem::linkToRoute("Par Doyennes", 'fa-solid fa-people-roof', 'admin_filtre_choix', ['filtre' => 'doyenne']),
            MenuItem::linkToRoute("Par Sections", 'fa-solid fa-people-group', 'admin_filtre_choix', ['filtre' => 'section']),
            MenuItem::linkToRoute("Par Grades", 'fa-solid fa-user-graduate', 'admin_filtre_choix', ['filtre' => 'grade']),
            MenuItem::linkTo(ParticipantCrudController::class, 'Confirmés', 'fa-solid fa-list')
                        ->setPermission('ROLE_SUPER_ADMIN'),
            MenuItem::linkTo(Participant2CrudController::class, 'Litiges', 'fa-solid fa-ban')
                        ->setPermission('ROLE_SUPER_ADMIN'),
        ]);
        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            yield MenuItem::section();
            yield MenuItem::section('GESTION');
            yield MenuItem::linkTo(VicariatCrudController::class, 'Vicariats', 'fa-solid fa-church');
            yield MenuItem::linkTo(DoyenneCrudController::class, 'Doyennes', 'fa-solid fa-people-roof');
            yield MenuItem::linkTo(SectionCrudController::class, 'Sections', 'fa-solid fa-people-group');
            yield MenuItem::linkTo(GradeCrudController::class, 'Grades', 'fa-solid fa-user-graduate');
        }

        if ($this->isGranted('ROLE_ADMIN'))
        {
            yield MenuItem::section();
            yield MenuItem::section('Sécurité');
            yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fa-solid fa-lock');

        }


    }
}
