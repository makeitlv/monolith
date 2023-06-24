<?php

declare(strict_types=1);

namespace App\Common\Presentation\Controller\Back;

use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Translation\TranslatableMessage;
use App\Common\Presentation\Translation\Back\TranslatableMessage as BackTranslatableMessage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use RuntimeException;
use Throwable;

abstract class AbstractController extends AbstractCrudController
{
    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->action($entityManager, $entityInstance, "createAction");
    }

    public function updateEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->action($entityManager, $entityInstance, "updateAction");
    }

    public function deleteEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->action($entityManager, $entityInstance, "deleteAction");
    }

    protected function createAction(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        throw new RuntimeException("Use command instead!");
    }

    protected function updateAction(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        throw new RuntimeException("Use command instead!");
    }

    protected function deleteAction(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        throw new RuntimeException("Use command instead!");
    }

    protected function addSuccessFlash(TranslatableMessage $message): void
    {
        $this->addFlash("success", $message);
    }

    protected function addInfoFlash(TranslatableMessage $message): void
    {
        $this->addFlash("info", $message);
    }

    protected function addWarningFlash(TranslatableMessage $message): void
    {
        $this->addFlash("warning", $message);
    }

    protected function addErrorFlash(TranslatableMessage $message): void
    {
        $this->addFlash("danger", $message);
    }

    private function action(EntityManagerInterface $entityManager, mixed $entityInstance, string $type): void
    {
        try {
            match ($type) {
                "createAction" => $this->createAction($entityManager, $entityInstance),
                "updateAction" => $this->updateAction($entityManager, $entityInstance),
                "deleteAction" => $this->deleteAction($entityManager, $entityInstance),
            };

            $translatableMessage = match ($type) {
                "createAction" => new BackTranslatableMessage('Content "%name%" has been created!', [
                    "%name%" => (string) $entityInstance,
                ]),
                "updateAction" => new BackTranslatableMessage('Content "%name%" has been updated!', [
                    "%name%" => (string) $entityInstance,
                ]),
                "deleteAction" => new BackTranslatableMessage('Content "%name%" has been deleted!', [
                    "%name%" => (string) $entityInstance,
                ]),
            };

            $this->addSuccessFlash($translatableMessage);
        } catch (RuntimeException $exception) {
            throw $exception;
        } catch (DomainException $exception) {
            $this->addErrorFlash($exception->getTranslatableMessage());
        } catch (Throwable $exception) {
            dump($exception);
            $this->addErrorFlash(
                new BackTranslatableMessage("Something went wrong! Please, contact with administrator to figure out.")
            );
        }
    }
}
