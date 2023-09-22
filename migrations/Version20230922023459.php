<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922023459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entretien (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, employeur_id INT DEFAULT NULL, date DATE DEFAULT NULL, duration INT DEFAULT NULL, INDEX IDX_2B58D6DA8D0EB82 (candidat_id), INDEX IDX_2B58D6DA5D7C53EC (employeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intressted_candidats (id INT AUTO_INCREMENT NOT NULL, employeur_id INT DEFAULT NULL, candidat_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_2F227B205D7C53EC (employeur_id), INDEX IDX_2F227B208D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intressted_offre (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, date DATE DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_9A29EEDA8D0EB82 (candidat_id), INDEX IDX_9A29EEDA4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA8D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA5D7C53EC FOREIGN KEY (employeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intressted_candidats ADD CONSTRAINT FK_2F227B205D7C53EC FOREIGN KEY (employeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intressted_candidats ADD CONSTRAINT FK_2F227B208D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intressted_offre ADD CONSTRAINT FK_9A29EEDA8D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intressted_offre ADD CONSTRAINT FK_9A29EEDA4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA8D0EB82');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA5D7C53EC');
        $this->addSql('ALTER TABLE intressted_candidats DROP FOREIGN KEY FK_2F227B205D7C53EC');
        $this->addSql('ALTER TABLE intressted_candidats DROP FOREIGN KEY FK_2F227B208D0EB82');
        $this->addSql('ALTER TABLE intressted_offre DROP FOREIGN KEY FK_9A29EEDA8D0EB82');
        $this->addSql('ALTER TABLE intressted_offre DROP FOREIGN KEY FK_9A29EEDA4CC8505A');
        $this->addSql('DROP TABLE entretien');
        $this->addSql('DROP TABLE intressted_candidats');
        $this->addSql('DROP TABLE intressted_offre');
    }
}
