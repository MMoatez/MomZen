<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211222114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossiermedical (id INT AUTO_INCREMENT NOT NULL, idpatient_id INT DEFAULT NULL, historique VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6699B4F0A6208F43 (idpatient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossiermedical ADD CONSTRAINT FK_6699B4F0A6208F43 FOREIGN KEY (idpatient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD rendezvous_id INT DEFAULT NULL, DROP is_verified');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493345E0A3 FOREIGN KEY (rendezvous_id) REFERENCES rendezvous (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6493345E0A3 ON user (rendezvous_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6493345E0A3');
        $this->addSql('ALTER TABLE dossiermedical DROP FOREIGN KEY FK_6699B4F0A6208F43');
        $this->addSql('DROP TABLE dossiermedical');
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('DROP INDEX IDX_8D93D6493345E0A3 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD is_verified TINYINT(1) NOT NULL, DROP rendezvous_id');
    }
}
