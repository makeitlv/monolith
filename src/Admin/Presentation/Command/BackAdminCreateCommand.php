<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Command;

use App\Common\Domain\Bus\Command\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Uid\Uuid;
use Throwable;

#[AsCommand(name: self::COMMAND_NAME, description: self::COMMAND_DESCRIPTION)]
class BackAdminCreateCommand extends Command
{
    private const COMMAND_NAME = "back:admin:create";
    private const COMMAND_DESCRIPTION = "Create super admin";

    public function __construct(private CommandBus $bus, string $name = self::COMMAND_NAME)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        return;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $email = (string) $io->ask("Please enter admin email");
            $firstname = (string) $io->ask("Please enter admin firstname");
            $lastname = (string) $io->ask("Please enter admin lastname");
            $password = (string) $io->askHidden("Please enter admin password");

            $this->bus->dispatch(
                new \App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperAdminCommand(
                    Uuid::v4()->__toString(),
                    $email,
                    $firstname,
                    $lastname,
                    $password
                )
            );
        } catch (HandlerFailedException $exception) {
            /** @var Throwable $previousException */
            $previousException = $exception->getPrevious();

            if ($previousException instanceof ValidationFailedException) {
                throw $previousException;
            }

            $io->error($previousException->getMessage());

            return Command::FAILURE;
        } catch (ValidationFailedException $exception) {
            foreach ($exception->getViolations() as $violation) {
                $io->error(sprintf("%s: %s", $violation->getPropertyPath(), (string) $violation->getMessage()));
            }
            $io->error($exception->getMessage());

            return Command::FAILURE;
        } catch (Throwable $exception) {
            error_log(var_export($exception::class, true));
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success("Super admin successfully created.");

        return Command::SUCCESS;
    }
}
