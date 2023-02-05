<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230204151353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time_tracking (id UUID NOT NULL, service_user_id UUID NOT NULL, service_description VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, service_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, service_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CF921D068EA8109 ON time_tracking (service_user_id)');
        $this->addSql('COMMENT ON COLUMN time_tracking.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.service_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.service_start IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.service_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D068EA8109 FOREIGN KEY (service_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE time_tracking DROP CONSTRAINT FK_CF921D068EA8109');
        $this->addSql('DROP TABLE time_tracking');
    }
}
