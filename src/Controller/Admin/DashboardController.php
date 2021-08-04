<?php

namespace App\Controller\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\ConferenceCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);//la cara para el admin sera conferencia aca podria ser graficos

        return $this->redirect($routeBuilder->setController(ConferenceCrudController::class)->generateUrl());

  
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Guestbook');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToRoute('Back to the website','fas fa-home','homepage');
        yield MenuItem::linkToCrud('Conferences','fas fa-map-marker-alt',Conference::class);
        yield MenuItem::linkToCrud('Comments','fas fa-comments',Comment::class);

    }
}
