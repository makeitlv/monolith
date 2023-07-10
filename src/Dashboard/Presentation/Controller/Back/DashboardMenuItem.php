<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use App\Admin\Presentation\Model\AdminModel;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DashboardMenuItem
{
    /**
     * @return iterable<mixed, \EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface>
     */
    public static function configureMenuItems(UrlGeneratorInterface $urlGenerator): iterable
    {
        yield MenuItem::section("Main");
        yield MenuItem::linkToDashboard("Dashboard", "fa fa-home");

        yield MenuItem::section("Content Management");
        yield MenuItem::linkToUrl("Translation", "fa fa-language", $urlGenerator->generate("translation_index"));

        yield MenuItem::section("Access Control");
        yield MenuItem::linkToCrud("Admin", "fa fa-user", AdminModel::class);

        yield MenuItem::section("Other");
        yield MenuItem::linkToUrl("Website", "fa fa-globe", $urlGenerator->generate("front.homepage"));
        yield MenuItem::linkToUrl("Logout", "fa fa-sign-out", $urlGenerator->generate("back.logout"));
    }
}
