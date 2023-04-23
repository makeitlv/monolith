<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route("/admin/dashboard", name: "back.dashboard")]
    public function index(): Response
    {
        return $this->render("back/page/dashboard/index.html.twig");
    }

    public function configureDashboard(): Dashboard
    {
        return DashboardSetting::configureDashboard(Dashboard::new());
    }

    public function configureCrud(): Crud
    {
        return DashboardCrud::configureCrud(parent::configureCrud());
    }

    public function configureMenuItems(): iterable
    {
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->container->get("router");

        return DashboardMenuItem::configureMenuItems($urlGenerator);
    }

    public function configureActions(): Actions
    {
        return DashboardAction::configureActions(parent::configureActions());
    }
}
