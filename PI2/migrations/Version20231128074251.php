<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128074251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (idAvis INT AUTO_INCREMENT NOT NULL, auteur VARCHAR(255) NOT NULL, note VARCHAR(255) NOT NULL, contenu VARCHAR(100) NOT NULL, PRIMARY KEY(idAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cartefidelite (user_id INT DEFAULT NULL, IdCarte INT AUTO_INCREMENT NOT NULL, PtsFidelite INT NOT NULL, DateDebut DATE NOT NULL, DateFin DATE NOT NULL, EtatCarte VARCHAR(9) NOT NULL, NiveauCarte VARCHAR(9) NOT NULL, UNIQUE INDEX UNIQ_CAC25E04A76ED395 (user_id), PRIMARY KEY(IdCarte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commantaire (IdCommentaire INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, IdEvennement INT NOT NULL, PRIMARY KEY(IdCommentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (idDemande INT AUTO_INCREMENT NOT NULL, Id INT NOT NULL, destination VARCHAR(255) NOT NULL, dateDepart DATE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(idDemande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (IdEvennement INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_depart DATE NOT NULL, prix INT NOT NULL, TypeEvennement VARCHAR(55) NOT NULL, guide_id INT NOT NULL, destination VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, SponsorEvenement VARCHAR(255) NOT NULL, PRIMARY KEY(IdEvennement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenements (IdEvenement INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_depart DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, TypeEvenement VARCHAR(255) NOT NULL, guide_id INT NOT NULL, destination VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, SponsorEvenement VARCHAR(255) NOT NULL, PRIMARY KEY(IdEvenement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offrespecialevenment (IdOffreSpecialEvenment INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_depart DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, guide_id VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, niveau VARCHAR(9) NOT NULL, PRIMARY KEY(IdOffreSpecialEvenment)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom TEXT NOT NULL, prenom TEXT NOT NULL, email TEXT NOT NULL, motDePasse TEXT NOT NULL, numeroTelephone TEXT NOT NULL, dateNaissance TEXT NOT NULL, genre TEXT NOT NULL, Role VARCHAR(20) DEFAULT \'CLIENT\' NOT NULL, cartefidelite_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cartefidelite ADD CONSTRAINT FK_CAC25E04A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartefidelite DROP FOREIGN KEY FK_CAC25E04A76ED395');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE cartefidelite');
        $this->addSql('DROP TABLE commantaire');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE evenements');
        $this->addSql('DROP TABLE offrespecialevenment');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
