<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323172635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(32) DEFAULT NULL, nom_prenoms VARCHAR(255) DEFAULT NULL, genre VARCHAR(10) DEFAULT NULL, age INT DEFAULT NULL, declarant_nom VARCHAR(255) DEFAULT NULL, declarant_contact VARCHAR(255) DEFAULT NULL, montant INT DEFAULT NULL, taille VARCHAR(255) DEFAULT NULL, profil VARCHAR(255) DEFAULT NULL, traitement VARCHAR(5) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, wave_id VARCHAR(255) DEFAULT NULL, wave_checkout_status VARCHAR(255) DEFAULT NULL, wave_client_reference VARCHAR(255) DEFAULT NULL, wave_payment_status VARCHAR(255) DEFAULT NULL, wave_transaction_id VARCHAR(255) DEFAULT NULL, wave_when_completed VARCHAR(255) DEFAULT NULL, wave_when_created VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, section_id INT DEFAULT NULL, grade_id INT DEFAULT NULL, INDEX IDX_D79F6B11D823E37A (section_id), INDEX IDX_D79F6B11FE19A1A8 (grade_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11D823E37A');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11FE19A1A8');
        $this->addSql('DROP TABLE participant');
    }
}
