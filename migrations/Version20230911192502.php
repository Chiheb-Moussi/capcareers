<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911192502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, autre_exigences LONGTEXT DEFAULT NULL, demarrage DATE DEFAULT NULL, profil VARCHAR(255) DEFAULT NULL, tjm DOUBLE PRECISION DEFAULT NULL, lieu VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_skill (offre_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_91703EC4CC8505A (offre_id), INDEX IDX_91703EC5585C142 (skill_id), PRIMARY KEY(offre_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteur (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offre_skill ADD CONSTRAINT FK_91703EC4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_skill ADD CONSTRAINT FK_91703EC5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre_skill DROP FOREIGN KEY FK_91703EC4CC8505A');
        $this->addSql('ALTER TABLE offre_skill DROP FOREIGN KEY FK_91703EC5585C142');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE offre_skill');
        $this->addSql('DROP TABLE secteur');
    }
}
