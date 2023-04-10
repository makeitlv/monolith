<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230410093705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Create admin table";
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE admin (uuid UUID NOT NULL, email VARCHAR(128) NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(32) NOT NULL, status VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(32) DEFAULT NULL, PRIMARY KEY(uuid))'
        );
        $this->addSql("CREATE UNIQUE INDEX UNIQ_880E0D76E7927C74 ON admin (email)");
        $this->addSql('COMMENT ON COLUMN admin.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN admin.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE admin");
    }
}
