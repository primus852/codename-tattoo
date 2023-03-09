<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309194936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config_price (id UUID NOT NULL, price_net DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(10) NOT NULL, time_from TIME(0) WITHOUT TIME ZONE NOT NULL, time_to TIME(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN config_price.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN config_price.time_from IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_price.time_to IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_price.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN config_price.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE config_price');
    }
}
