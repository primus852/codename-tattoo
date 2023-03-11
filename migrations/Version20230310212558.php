<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310212558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id UUID NOT NULL, name VARCHAR(255) NOT NULL, name_short VARCHAR(255) NOT NULL, client_number VARCHAR(15) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404555E237E06 ON client (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455410EC2E7 ON client (name_short)');
        $this->addSql('COMMENT ON COLUMN client.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN client.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN client.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE price (id UUID NOT NULL, price_net DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(10) NOT NULL, time_from TIME(0) WITHOUT TIME ZONE NOT NULL, time_to TIME(0) WITHOUT TIME ZONE NOT NULL, week_day INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN price.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN price.time_from IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN price.time_to IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN price.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN price.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE time_tracking (id UUID NOT NULL, service_user_id UUID NOT NULL, client_id UUID NOT NULL, override_price_id UUID DEFAULT NULL, service_description VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, service_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, service_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CF921D068EA8109 ON time_tracking (service_user_id)');
        $this->addSql('CREATE INDEX IDX_CF921D019EB6921 ON time_tracking (client_id)');
        $this->addSql('CREATE INDEX IDX_CF921D02C8AE0EA ON time_tracking (override_price_id)');
        $this->addSql('COMMENT ON COLUMN time_tracking.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.service_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.override_price_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.service_start IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.service_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_tracking.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, code VARCHAR(15) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64977153098 ON "user" (code)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D068EA8109 FOREIGN KEY (service_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D019EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D02C8AE0EA FOREIGN KEY (override_price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE time_tracking DROP CONSTRAINT FK_CF921D068EA8109');
        $this->addSql('ALTER TABLE time_tracking DROP CONSTRAINT FK_CF921D019EB6921');
        $this->addSql('ALTER TABLE time_tracking DROP CONSTRAINT FK_CF921D02C8AE0EA');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE time_tracking');
        $this->addSql('DROP TABLE "user"');
    }
}
