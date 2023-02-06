<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206190204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_tracking ADD override_rate_hour_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN time_tracking.override_rate_hour_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D012930036 FOREIGN KEY (override_rate_hour_id) REFERENCES config_rate_hours (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CF921D012930036 ON time_tracking (override_rate_hour_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE time_tracking DROP CONSTRAINT FK_CF921D012930036');
        $this->addSql('DROP INDEX IDX_CF921D012930036');
        $this->addSql('ALTER TABLE time_tracking DROP override_rate_hour_id');
    }
}
