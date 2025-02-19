<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218221048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ambulance (id INT AUTO_INCREMENT NOT NULL, chauffeur_id INT DEFAULT NULL, immatriculation VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, INDEX IDX_4F20B42E85C0B3BE (chauffeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analyse (id INT AUTO_INCREMENT NOT NULL, dossier_medicale_id INT DEFAULT NULL, risque_grosses VARCHAR(255) NOT NULL, urgence_medicale TINYINT(1) NOT NULL, INDEX IDX_351B0C7EF2C46B04 (dossier_medicale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image VARCHAR(255) DEFAULT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, forum_id INT NOT NULL, contenu LONGTEXT NOT NULL, date_publication DATETIME NOT NULL, INDEX IDX_67F068BC29CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, id_dossier INT NOT NULL, id_med INT NOT NULL, ordonnance VARCHAR(255) DEFAULT NULL, INDEX IDX_964685A6E3D54947 (id_dossier), INDEX IDX_964685A673BF5398 (id_med), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_medicall (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, datecreation DATETIME NOT NULL, grosses_semaine INT NOT NULL, symptotes VARCHAR(255) NOT NULL, INDEX IDX_358F43646B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossiermedical (id INT AUTO_INCREMENT NOT NULL, idpatient_id INT DEFAULT NULL, historique VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6699B4F0A6208F43 (idpatient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, date_publication DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_CE606404A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, idpatient_id INT NOT NULL, idmedecin_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', adresse VARCHAR(255) DEFAULT NULL, domicile TINYINT(1) NOT NULL, realise TINYINT(1) DEFAULT NULL, INDEX IDX_C09A9BA8A6208F43 (idpatient_id), INDEX IDX_C09A9BA8C95A07BE (idmedecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT NOT NULL, admin_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3E7B0BFB2D6BA2D9 (reclamation_id), INDEX IDX_3E7B0BFB642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, ambulance_id INT DEFAULT NULL, date_depart DATETIME NOT NULL, emplacement_client VARCHAR(255) NOT NULL, INDEX IDX_3F9D8955EF55E5E1 (ambulance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ambulance ADD CONSTRAINT FK_4F20B42E85C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE analyse ADD CONSTRAINT FK_351B0C7EF2C46B04 FOREIGN KEY (dossier_medicale_id) REFERENCES dossier_medicall (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6E3D54947 FOREIGN KEY (id_dossier) REFERENCES dossiermedical (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A673BF5398 FOREIGN KEY (id_med) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dossier_medicall ADD CONSTRAINT FK_358F43646B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dossiermedical ADD CONSTRAINT FK_6699B4F0A6208F43 FOREIGN KEY (idpatient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8A6208F43 FOREIGN KEY (idpatient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8C95A07BE FOREIGN KEY (idmedecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB2D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D8955EF55E5E1 FOREIGN KEY (ambulance_id) REFERENCES ambulance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambulance DROP FOREIGN KEY FK_4F20B42E85C0B3BE');
        $this->addSql('ALTER TABLE analyse DROP FOREIGN KEY FK_351B0C7EF2C46B04');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC29CCBAD0');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6E3D54947');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A673BF5398');
        $this->addSql('ALTER TABLE dossier_medicall DROP FOREIGN KEY FK_358F43646B899279');
        $this->addSql('ALTER TABLE dossiermedical DROP FOREIGN KEY FK_6699B4F0A6208F43');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA8A6208F43');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA8C95A07BE');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB2D6BA2D9');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB642B8210');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D8955EF55E5E1');
        $this->addSql('DROP TABLE ambulance');
        $this->addSql('DROP TABLE analyse');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE dossier_medicall');
        $this->addSql('DROP TABLE dossiermedical');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE voyage');
    }
}
