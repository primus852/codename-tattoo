<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205113448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config_rate_hours (id UUID NOT NULL, hour_from TIME(0) WITHOUT TIME ZONE NOT NULL, hour_to TIME(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN config_rate_hours.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN config_rate_hours.hour_from IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_rate_hours.hour_to IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_rate_hours.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_rate_hours.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE config_rate_hours');
    }
}
