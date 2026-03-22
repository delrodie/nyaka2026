<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323133139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doyenne ADD uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE grade ADD uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE section ADD uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vicariat ADD uuid VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doyenne DROP uuid');
        $this->addSql('ALTER TABLE grade DROP uuid');
        $this->addSql('ALTER TABLE section DROP uuid');
        $this->addSql('ALTER TABLE vicariat DROP uuid');
    }
}
