<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217205751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response ADD admin_id INT NOT NULL, ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFB642B8210 ON response (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB642B8210');
        $this->addSql('DROP INDEX IDX_3E7B0BFB642B8210 ON response');
        $this->addSql('ALTER TABLE response DROP admin_id, DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL');
    }
}
