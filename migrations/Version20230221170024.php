<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221170024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        for ($i = 1; $i <= 7; $i++) {
            $uuid = Uuid::v4();
            $time_p = new \DateTimeImmutable();
            $time = $time_p->format('Y-m-d H:i:s');
            $this->addSql('INSERT INTO config_week_days (id, day_of_week, updated_at, created_at) VALUES (\'' . $uuid . '\', ' . $i . ', \'' . $time . '\', \'' . $time . '\')');
        }

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM config_week_days WHERE day_of_week <= 7');
    }
}
