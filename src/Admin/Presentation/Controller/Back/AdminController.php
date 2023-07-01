<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Back;

use App\Admin\Application\UseCase\Command\Activate\ActivateAdminCommand;
use App\Admin\Application\UseCase\Command\Block\BlockAdminCommand;
use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteAdminCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateAdminCommand;
use App\Admin\Application\UseCase\Command\GeneratePassword\GenerateAdminPasswordCommand;
use App\Admin\Application\UseCase\Command\Unblock\UnblockAdminCommand;
use App\Admin\Application\UseCase\Command\UpdatePassword\UpdateAdminPasswordCommand;
use App\Admin\Presentation\Model\AdminModel;
use App\Admin\Infrastructure\Adapter\Security\SecurityAdapter;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Common\Presentation\Controller\Back\AbstractController;
use App\Common\Presentation\Translation\Back\TranslatableMessage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminController extends AbstractController
{
    public function __construct(private SecurityAdapter $securityAdapter, private CommandBus $bus)
    {
    }

    public static function getEntityFqcn(): string
    {
        return AdminModel::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return AdminAction::configureActions(parent::configureActions($actions), $this->securityAdapter->getAdmin());
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var AdminModel $admin */
        $admin = $this->getContext()
            ?->getEntity()
            ?->getInstance();

        $currentAdmin = false;
        if (
            $this->getContext()
                ?->getCrud()
                ?->getCurrentPage() === Crud::PAGE_EDIT &&
            $admin->uuid === $this->securityAdapter->getAdmin()->uuid
        ) {
            $currentAdmin = true;
        }

        return AdminField::configureFields($currentAdmin);
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

        if ($entityInstance->newPassword) {
            $this->bus->dispatch(new UpdateAdminPasswordCommand($entityInstance->uuid, $entityInstance->newPassword));
        }
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

    public function activateAction(AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator): Response
    {
        return $this->customAction($adminContext, $adminUrlGenerator, function (AdminContext $adminContext) {
            /** @var AdminModel $admin */
            $admin = $adminContext->getEntity()->getInstance();
            $this->bus->dispatch(new ActivateAdminCommand($admin->uuid));

            $this->addFlash("success", new TranslatableMessage("Admin activated."));
        });
    }

    public function blockAction(AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator): Response
    {
        return $this->customAction($adminContext, $adminUrlGenerator, function (AdminContext $adminContext) {
            /** @var AdminModel $admin */
            $admin = $adminContext->getEntity()->getInstance();
            $this->bus->dispatch(new BlockAdminCommand($admin->uuid));

            $this->addFlash("success", new TranslatableMessage("Admin blocked."));
        });
    }

    public function unblockAction(AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator): Response
    {
        return $this->customAction($adminContext, $adminUrlGenerator, function (AdminContext $adminContext) {
            /** @var AdminModel $admin */
            $admin = $adminContext->getEntity()->getInstance();
            $this->bus->dispatch(new UnblockAdminCommand($admin->uuid));

            $this->addFlash("success", new TranslatableMessage("Admin unblocked."));
        });
    }

    public function resetPasswordAction(AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator): Response
    {
        return $this->customAction($adminContext, $adminUrlGenerator, function (AdminContext $adminContext) {
            /** @var AdminModel $admin */
            $admin = $adminContext->getEntity()->getInstance();
            $this->bus->dispatch(
                new GenerateAdminPasswordCommand($admin->uuid, $admin->email, $admin->firstname, $admin->lastname)
            );

            $this->addFlash("success", new TranslatableMessage("Admin password reset."));
        });
    }

    private function customAction(
        AdminContext $adminContext,
        AdminUrlGenerator $adminUrlGenerator,
        callable $action
    ): Response {
        $entityInstance = $adminContext->getEntity()->getInstance();
        if (!$entityInstance instanceof AdminModel) {
            throw new RuntimeException(
                sprintf("Wrong entity instance! Should be %s got %s", AdminModel::class, $entityInstance::class)
            );
        }

        $action($adminContext);

        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_DETAIL)
            ->setEntityId($entityInstance->uuid)
            ->generateUrl();

        return $this->redirect($targetUrl);
    }
}
