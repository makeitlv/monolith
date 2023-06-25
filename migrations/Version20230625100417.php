<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230625100417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Added new admin field - passwordSecure";
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE admin ADD password_secure BOOLEAN NOT NULL DEFAULT false");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE admin DROP password_secure");
    }
}
