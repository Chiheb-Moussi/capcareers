<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926203036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entretien_date (id INT AUTO_INCREMENT NOT NULL, entretien_id INT DEFAULT NULL, confirmed TINYINT(1) DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_231E44BD548DCEA2 (entretien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entretien_date ADD CONSTRAINT FK_231E44BD548DCEA2 FOREIGN KEY (entretien_id) REFERENCES entretien (id)');
        $this->addSql('ALTER TABLE entretien CHANGE date date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien_date DROP FOREIGN KEY FK_231E44BD548DCEA2');
        $this->addSql('DROP TABLE entretien_date');
        $this->addSql('ALTER TABLE entretien CHANGE date date DATE DEFAULT NULL');
    }
}
