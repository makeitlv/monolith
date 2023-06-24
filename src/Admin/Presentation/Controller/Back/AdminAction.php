<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Back;

use App\Admin\Domain\DataTransfer\AdminDTO;
use App\Admin\Presentation\Model\AdminModel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AdminAction
{
    private const ACTIVATE = "activateAction";
    private const BLOCK = "blockAction";
    private const RESET_PASSWORD = "resetPasswordAction";

    public static function configureActions(Actions $action, AdminDTO $admin): Actions
    {
        return $action
            ->add(Crud::PAGE_DETAIL, self::createResetPasswordAction($admin))
            ->add(Crud::PAGE_DETAIL, self::createBlockAction())
            ->add(Crud::PAGE_DETAIL, self::createActivateAction())
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->displayIf(static function (AdminModel $admin): bool {
                    return $admin->status === "blocked";
                });
            });
    }

    private static function createActivateAction(): Action
    {
        return Action::new(self::ACTIVATE, "Activate", "fa fa-lock-open")
            ->linkToCrudAction(self::ACTIVATE)
            ->displayIf(static function (AdminModel $admin): bool {
                return $admin->status !== "activated" && $admin->status !== "blocked";
            });
    }

    private static function createBlockAction(): Action
    {
        return Action::new(self::BLOCK, "Block", "fa fa-lock")
            ->linkToCrudAction(self::BLOCK)
            ->displayIf(static function (AdminModel $admin): bool {
                return $admin->status !== "blocked" && !\in_array("ROLE_SUPER_ADMIN", $admin->getRole(), true);
            });
    }

    private static function createResetPasswordAction(AdminDTO $loggedAdmin): Action
    {
        return Action::new(self::RESET_PASSWORD, "Reset Password", "fa fa-passport")
            ->linkToCrudAction(self::RESET_PASSWORD)
            ->displayIf(static function (AdminModel $admin) use ($loggedAdmin): bool {
                if ($admin->uuid === $loggedAdmin->uuid) {
                    return false;
                }

                return $admin->status !== "blocked" && !\in_array("ROLE_SUPER_ADMIN", $admin->getRole(), true);
            });
    }
}
