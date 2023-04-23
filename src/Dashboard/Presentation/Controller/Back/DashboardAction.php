<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardAction
{
    public static function configureActions(Actions $action): Actions
    {
        return $action
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)

            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

            ->update(Crud::PAGE_DETAIL, Action::EDIT, static function (Action $action) {
                return $action->setIcon("fa fa-edit");
            })
            //   ->update(Crud::PAGE_DETAIL, Action::DELETE, static function (Action $action) {
            //       return $action->setCssClass("btn btn-danger");
            //   })
            ->update(Crud::PAGE_DETAIL, Action::INDEX, static function (Action $action) {
                return $action->setIcon("fa fa-list");
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, static function (Action $action) {
                return $action->setIcon("fa fa-edit");
            })
            ->update(Crud::PAGE_EDIT, Action::INDEX, static function (Action $action) {
                return $action->setIcon("fa fa-list");
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, static function (Action $action) {
                return $action->setIcon("fa fa-edit");
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, static function (Action $action) {
                return $action->setIcon("fa fa-edit");
            })
            ->update(Crud::PAGE_NEW, Action::INDEX, static function (Action $action) {
                return $action->setIcon("fa fa-list");
            })

            ->reorder(Crud::PAGE_DETAIL, [Action::EDIT, Action::DELETE, Action::INDEX])
            ->reorder(Crud::PAGE_NEW, [Action::SAVE_AND_RETURN, Action::SAVE_AND_ADD_ANOTHER, Action::INDEX])
            ->reorder(Crud::PAGE_EDIT, [Action::SAVE_AND_RETURN, Action::SAVE_AND_CONTINUE, Action::INDEX]);
    }
}
