<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use App\Admin\Presentation\Controller\Back\AdminController;
use App\Dashboard\Domain\DataTransfer\AdminDTO;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;

class DashboardUserMenu
{
    public static function configureUserMenu(UserMenu $menu, AdminDTO $admin, AdminUrlGenerator $urlGenerator): UserMenu
    {
        return $menu->addMenuItems(self::configureMenu($admin, $urlGenerator));
    }

    /** @return MenuItemInterface[] */
    private static function configureMenu(AdminDTO $admin, AdminUrlGenerator $urlGenerator): array
    {
        return [
            MenuItem::linkToUrl(
                "User Profile",
                null,
                $urlGenerator
                    ->setController(AdminController::class)
                    ->setAction(Action::DETAIL)
                    ->setEntityId($admin->uuid)
                    ->generateUrl()
            ),
        ];
    }
}
