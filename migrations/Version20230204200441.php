<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230204200441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_tracking ADD client_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN time_tracking.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D019EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CF921D019EB6921 ON time_tracking (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE time_tracking DROP CONSTRAINT FK_CF921D019EB6921');
        $this->addSql('DROP INDEX IDX_CF921D019EB6921');
        $this->addSql('ALTER TABLE time_tracking DROP client_id');
    }
}
