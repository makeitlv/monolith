<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class AdminField
{
    /**
     * @return iterable<mixed, \EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface>
     */
    public static function configureFields(): iterable
    {
        yield Field::new("uuid")->onlyOnDetail();

        yield EmailField::new("email")->setSortable(false);
        yield Field::new("firstname")->setSortable(false);
        yield Field::new("lastname")->setSortable(false);

        yield ArrayField::new("role")
            ->hideOnForm()
            ->setSortable(false);

        yield ChoiceField::new("status")
            ->setChoices([
                "Activated" => "activated",
                "Blocked" => "blocked",
                "Disabled" => "disabled",
            ])
            ->renderAsBadges([
                "activated" => "success",
                "blocked" => "warning",
                "disabled" => "danger",
            ])
            ->hideOnForm()
            ->setSortable(false);

        yield Field::new("createdAt")->hideOnForm();
        yield Field::new("updatedAt")->hideOnForm();
    }
}
