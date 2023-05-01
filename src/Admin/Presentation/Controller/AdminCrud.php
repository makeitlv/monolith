<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller;

use App\Admin\Presentation\Model\AdminModel;
use App\Common\Presentation\Translation\Back\TranslatableMessage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AdminCrud
{
    public static function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_DETAIL, static function (AdminModel $admin) {
                return new TranslatableMessage("Display %email%", ["%email%" => $admin->email]);
            })
            ->setPageTitle(Crud::PAGE_EDIT, static function (AdminModel $admin) {
                return new TranslatableMessage("Edit %email%", ["%email%" => $admin->email]);
            })
            ->setSearchFields(["email", "firstname", "lastname"])
            ->setEntityLabelInSingular("Admin")
            ->setEntityLabelInPlural("Admin");
    }
}
