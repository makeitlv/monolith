<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use App\Common\Presentation\Translation\Back\TranslatableMessage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route("/admin/dashboard", name: "back.dashboard")]
    public function index(): Response
    {
        return $this->render("back/page/dashboard/index.html.twig");
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("AdminPanel")
            ->setTranslationDomain(TranslatableMessage::DOMAIN);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section("Main");
        yield MenuItem::linkToDashboard("Dashboard", "fa fa-home");

        yield MenuItem::section("Other");
        yield MenuItem::linkToUrl("Website", "fa fa-globe", "/");
    }
}
