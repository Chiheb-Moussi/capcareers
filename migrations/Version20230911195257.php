<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911195257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD employeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F5D7C53EC FOREIGN KEY (employeur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F5D7C53EC ON offre (employeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F5D7C53EC');
        $this->addSql('DROP INDEX IDX_AF86866F5D7C53EC ON offre');
        $this->addSql('ALTER TABLE offre DROP employeur_id');
    }
}
