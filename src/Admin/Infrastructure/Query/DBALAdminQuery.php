<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Query;

use Doctrine\DBAL\Connection;
use App\Admin\Domain\DataTransfer\AdminDTO;
use App\Admin\Domain\Query\AdminQueryInterface;

final class DBALAdminQuery implements AdminQueryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findByEmail(string $email): ?AdminDTO
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select("*")
            ->from("admin")
            ->where("email = :email")
            ->setParameter("email", $email);

        $row = $queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return new AdminDTO(
            (string) $row["uuid"],
            (string) $row["email"],
            (string) $row["firstname"],
            (string) $row["lastname"],
            (string) $row["password"],
            (string) $row["role"],
            (string) $row["status"]
        );
    }
}
