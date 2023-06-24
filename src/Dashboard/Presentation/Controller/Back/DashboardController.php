<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use App\Dashboard\Infrastructure\Adapter\Security\SecurityAdapter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private SecurityAdapter $securityAdapter)
    {
    }

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

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        /** @var AdminUrlGenerator $urlGenerator */
        $urlGenerator = $this->container->get(AdminUrlGenerator::class);

        return DashboardUserMenu::configureUserMenu(
            parent::configureUserMenu($user),
            $this->securityAdapter->getAdmin(),
            $urlGenerator
        );
    }
}
