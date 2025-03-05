<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305000654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_ambulance (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, ambulance_id INT NOT NULL, statut VARCHAR(20) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_3A0C4EFBA76ED395 (user_id), INDEX IDX_3A0C4EFBEF55E5E1 (ambulance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_ambulance ADD CONSTRAINT FK_3A0C4EFBA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE demande_ambulance ADD CONSTRAINT FK_3A0C4EFBEF55E5E1 FOREIGN KEY (ambulance_id) REFERENCES ambulance (id)');
        $this->addSql('ALTER TABLE ambulance ADD latitude DOUBLE PRECISION DEFAULT NULL, ADD longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD likes INT DEFAULT 0 NOT NULL, ADD dislikes INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_ambulance DROP FOREIGN KEY FK_3A0C4EFBA76ED395');
        $this->addSql('ALTER TABLE demande_ambulance DROP FOREIGN KEY FK_3A0C4EFBEF55E5E1');
        $this->addSql('DROP TABLE demande_ambulance');
        $this->addSql('ALTER TABLE ambulance DROP latitude, DROP longitude');
        $this->addSql('ALTER TABLE commentaire DROP likes, DROP dislikes');
    }
}
