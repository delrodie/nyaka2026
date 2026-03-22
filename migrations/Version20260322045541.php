<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322045541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doyenne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, code VARCHAR(10) DEFAULT NULL, vicariat_id INT DEFAULT NULL, INDEX IDX_67E2BA5CA44EF126 (vicariat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, code VARCHAR(10) DEFAULT NULL, doyenne_id INT DEFAULT NULL, INDEX IDX_2D737AEF53C9D7E5 (doyenne_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vicariat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, code VARCHAR(10) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE doyenne ADD CONSTRAINT FK_67E2BA5CA44EF126 FOREIGN KEY (vicariat_id) REFERENCES vicariat (id)');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF53C9D7E5 FOREIGN KEY (doyenne_id) REFERENCES doyenne (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doyenne DROP FOREIGN KEY FK_67E2BA5CA44EF126');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF53C9D7E5');
        $this->addSql('DROP TABLE doyenne');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE vicariat');
    }
}
