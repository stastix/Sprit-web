<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112013408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, text_reclamation VARCHAR(255) NOT NULL, cible_reclamation VARCHAR(255) NOT NULL, date_reclamation DATE NOT NULL, etat_reclamation VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, nbrseat INT NOT NULL, dateshow DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theatre_play (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, duration VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_reader DROP FOREIGN KEY FK_E5E882B11717D737');
        $this->addSql('ALTER TABLE book_reader DROP FOREIGN KEY FK_E5E882B1E5124735');
        $this->addSql('DROP TABLE book_reader');
        $this->addSql('DROP TABLE reader');
        $this->addSql('ALTER TABLE book CHANGE author_id author_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_reader (reader_id INT NOT NULL, book_ref INT NOT NULL, INDEX IDX_E5E882B11717D737 (reader_id), INDEX IDX_E5E882B1E5124735 (book_ref), PRIMARY KEY(reader_id, book_ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reader (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE book_reader ADD CONSTRAINT FK_E5E882B11717D737 FOREIGN KEY (reader_id) REFERENCES reader (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_reader ADD CONSTRAINT FK_E5E882B1E5124735 FOREIGN KEY (book_ref) REFERENCES book (ref)');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE `show`');
        $this->addSql('DROP TABLE theatre_play');
        $this->addSql('ALTER TABLE book CHANGE author_id author_id INT NOT NULL');
    }
}
