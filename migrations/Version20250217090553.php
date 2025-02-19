<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217090553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ambulance (id INT AUTO_INCREMENT NOT NULL, chauffeur_id INT DEFAULT NULL, immatriculation VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, INDEX IDX_4F20B42E85C0B3BE (chauffeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, ambulance_id INT DEFAULT NULL, date_depart DATETIME NOT NULL, emplacement_client VARCHAR(255) NOT NULL, INDEX IDX_3F9D8955EF55E5E1 (ambulance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ambulance ADD CONSTRAINT FK_4F20B42E85C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D8955EF55E5E1 FOREIGN KEY (ambulance_id) REFERENCES ambulance (id)');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE reponse');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT NOT NULL, contenu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_commentaire DATE NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE forum (id INT NOT NULL, nom_forum VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nb_comm INT NOT NULL, date_creation DATE NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reponse (id INT NOT NULL, contenu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_reponse DATE NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ambulance DROP FOREIGN KEY FK_4F20B42E85C0B3BE');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D8955EF55E5E1');
        $this->addSql('DROP TABLE ambulance');
        $this->addSql('DROP TABLE voyage');
    }
}
