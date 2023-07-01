<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Back;

use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use App\Common\Presentation\Form\NewPasswordType;

class AdminField
{
    /**
     * @return iterable<mixed, \EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface>
     */
    public static function configureFields(bool $currentAdmin): iterable
    {
        yield FormField::addPanel("Basic Data");

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
                "blocked" => "danger",
                "disabled" => "warning",
            ])
            ->hideOnForm()
            ->setSortable(false);

        yield Field::new("createdAt")->hideOnForm();
        yield Field::new("updatedAt")->hideOnForm();

        if ($currentAdmin) {
            yield FormField::addPanel("Change Password")->onlyWhenUpdating();

            yield Field::new("password", "")
                ->onlyWhenUpdating()
                ->setRequired(false)
                ->setFormType(NewPasswordType::class);
        }
    }
}
