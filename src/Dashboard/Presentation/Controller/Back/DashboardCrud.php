<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardCrud
{
    public static function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort([
                "createdAt" => "DESC",
            ])
            ->showEntityActionsInlined()
            ->setSearchFields(null);
    }
}
