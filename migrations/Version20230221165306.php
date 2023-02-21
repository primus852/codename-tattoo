<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221165306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config_week_days (id UUID NOT NULL, day_of_week INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN config_week_days.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN config_week_days.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_week_days.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE config_week_days_config_rate_hours (config_week_days_id UUID NOT NULL, config_rate_hours_id UUID NOT NULL, PRIMARY KEY(config_week_days_id, config_rate_hours_id))');
        $this->addSql('CREATE INDEX IDX_39D54ECC146721D9 ON config_week_days_config_rate_hours (config_week_days_id)');
        $this->addSql('CREATE INDEX IDX_39D54ECC3AC09479 ON config_week_days_config_rate_hours (config_rate_hours_id)');
        $this->addSql('COMMENT ON COLUMN config_week_days_config_rate_hours.config_week_days_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN config_week_days_config_rate_hours.config_rate_hours_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE config_week_days_config_rate_hours ADD CONSTRAINT FK_39D54ECC146721D9 FOREIGN KEY (config_week_days_id) REFERENCES config_week_days (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config_week_days_config_rate_hours ADD CONSTRAINT FK_39D54ECC3AC09479 FOREIGN KEY (config_rate_hours_id) REFERENCES config_rate_hours (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE config_week_days_config_rate_hours DROP CONSTRAINT FK_39D54ECC146721D9');
        $this->addSql('ALTER TABLE config_week_days_config_rate_hours DROP CONSTRAINT FK_39D54ECC3AC09479');
        $this->addSql('DROP TABLE config_week_days');
        $this->addSql('DROP TABLE config_week_days_config_rate_hours');
    }
}
