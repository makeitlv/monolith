<?php

declare(strict_types=1);

namespace App\Dashboard\Presentation\Controller\Back;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;

class DashboardSetting
{
    public static function configureDashboard(Dashboard $dashboard): Dashboard
    {
        return $dashboard->setTitle("AdminPanel")->setTranslationDomain("back");
    }
}
