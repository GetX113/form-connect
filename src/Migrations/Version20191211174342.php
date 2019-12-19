<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191211174342 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_formateur (id INT AUTO_INCREMENT NOT NULL, formateur_id INT NOT NULL, classement INT NOT NULL, UNIQUE INDEX UNIQ_F19B9E5F155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_societe (id INT AUTO_INCREMENT NOT NULL, societe_id INT NOT NULL, classement INT NOT NULL, UNIQUE INDEX UNIQ_2601A169FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE best_formateur ADD CONSTRAINT FK_F19B9E5F155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE best_societe ADD CONSTRAINT FK_2601A169FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE departement ADD numero INT NOT NULL, ADD region VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE disponibilite DROP frais_deplacement, DROP frais_repas, DROP frais_hotel, DROP status');
        $this->addSql('ALTER TABLE formateur ADD frais_repas INT DEFAULT NULL, ADD frais_hotel INT DEFAULT NULL, ADD frais_deplacement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE societe ADD logo VARCHAR(1024) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE best_formateur');
        $this->addSql('DROP TABLE best_societe');
        $this->addSql('ALTER TABLE departement DROP numero, DROP region');
        $this->addSql('ALTER TABLE disponibilite ADD frais_deplacement DOUBLE PRECISION DEFAULT NULL, ADD frais_repas DOUBLE PRECISION DEFAULT NULL, ADD frais_hotel DOUBLE PRECISION DEFAULT NULL, ADD status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE formateur DROP frais_repas, DROP frais_hotel, DROP frais_deplacement');
        $this->addSql('ALTER TABLE societe DROP logo');
    }
}
