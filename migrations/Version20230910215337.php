<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910215337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat_info (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, disponibilite DATE DEFAULT NULL, tjm DOUBLE PRECISION DEFAULT NULL, type_profile VARCHAR(255) DEFAULT NULL, nombre_exp INT DEFAULT NULL, type_contrat VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_9C89EE288D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidat_info_skill (id INT AUTO_INCREMENT NOT NULL, skill_id INT NOT NULL, candidat_info_id INT NOT NULL, note INT DEFAULT NULL, INDEX IDX_CC464E2B5585C142 (skill_id), INDEX IDX_CC464E2B4863712 (candidat_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, candidat_info_id INT NOT NULL, nom_entreprise VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) NOT NULL, nombre_annee INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_590C1034863712 (candidat_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_skill (experience_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_3D6F986146E90E27 (experience_id), INDEX IDX_3D6F98615585C142 (skill_id), PRIMARY KEY(experience_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, candidat_info_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date_formation DATE DEFAULT NULL, INDEX IDX_404021BF4863712 (candidat_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidat_info ADD CONSTRAINT FK_9C89EE288D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidat_info_skill ADD CONSTRAINT FK_CC464E2B5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE candidat_info_skill ADD CONSTRAINT FK_CC464E2B4863712 FOREIGN KEY (candidat_info_id) REFERENCES candidat_info (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1034863712 FOREIGN KEY (candidat_info_id) REFERENCES candidat_info (id)');
        $this->addSql('ALTER TABLE experience_skill ADD CONSTRAINT FK_3D6F986146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_skill ADD CONSTRAINT FK_3D6F98615585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF4863712 FOREIGN KEY (candidat_info_id) REFERENCES candidat_info (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat_info DROP FOREIGN KEY FK_9C89EE288D0EB82');
        $this->addSql('ALTER TABLE candidat_info_skill DROP FOREIGN KEY FK_CC464E2B5585C142');
        $this->addSql('ALTER TABLE candidat_info_skill DROP FOREIGN KEY FK_CC464E2B4863712');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1034863712');
        $this->addSql('ALTER TABLE experience_skill DROP FOREIGN KEY FK_3D6F986146E90E27');
        $this->addSql('ALTER TABLE experience_skill DROP FOREIGN KEY FK_3D6F98615585C142');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF4863712');
        $this->addSql('DROP TABLE candidat_info');
        $this->addSql('DROP TABLE candidat_info_skill');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_skill');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE skill');
    }
}
