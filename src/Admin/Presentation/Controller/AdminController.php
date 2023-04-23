<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller;

use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteAdminCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateAdminCommand;
use App\Admin\Presentation\Model\AdminModel;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Common\Presentation\Controller\Back\AbstractController;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use RuntimeException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminController extends AbstractController
{
    public function __construct(private CommandBus $bus)
    {
    }

    public static function getEntityFqcn(): string
    {
        return AdminModel::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return AdminField::configureFields();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return AdminCrud::configureCrud(parent::configureCrud($crud));
    }

    public function createAction(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if (!$entityInstance instanceof AdminModel) {
            throw new RuntimeException(
                sprintf("Wrong entity instance! Should be %s got %s", AdminModel::class, $entityInstance::class)
            );
        }

        $this->bus->dispatch(
            new CreateAdminCommand(
                Uuid::v4()->__toString(),
                $entityInstance->email,
                $entityInstance->firstname,
                $entityInstance->lastname
            )
        );
    }

    public function updateAction(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if (!$entityInstance instanceof AdminModel) {
            throw new RuntimeException(
                sprintf("Wrong entity instance! Should be %s got %s", AdminModel::class, $entityInstance::class)
            );
        }

        $this->bus->dispatch(
            new UpdateAdminCommand(
                $entityInstance->uuid,
                $entityInstance->email,
                $entityInstance->firstname,
                $entityInstance->lastname
            )
        );
    }

    public function deleteAction(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if (!$entityInstance instanceof AdminModel) {
            throw new RuntimeException(
                sprintf("Wrong entity instance! Should be %s got %s", AdminModel::class, $entityInstance::class)
            );
        }

        $this->bus->dispatch(new DeleteAdminCommand($entityInstance->uuid));
    }
}
