<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191214112756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE best_formateur');
        $this->addSql('DROP TABLE best_formation');
        $this->addSql('DROP TABLE best_societe');
        $this->addSql('ALTER TABLE departement CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE domain_formation ADD classement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formateur ADD classement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE societe ADD last_login DATETIME DEFAULT NULL, ADD classement INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE best_formateur (id INT AUTO_INCREMENT NOT NULL, formateur_id INT NOT NULL, classement INT NOT NULL, UNIQUE INDEX UNIQ_F19B9E5F155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE best_formation (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, classement INT NOT NULL, UNIQUE INDEX UNIQ_5CADC1AF5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE best_societe (id INT AUTO_INCREMENT NOT NULL, societe_id INT NOT NULL, classement INT NOT NULL, UNIQUE INDEX UNIQ_2601A169FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE best_formateur ADD CONSTRAINT FK_F19B9E5F155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE best_formation ADD CONSTRAINT FK_5CADC1AF5200282E FOREIGN KEY (formation_id) REFERENCES domain_formation (id)');
        $this->addSql('ALTER TABLE best_societe ADD CONSTRAINT FK_2601A169FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE departement CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE domain_formation DROP classement');
        $this->addSql('ALTER TABLE formateur DROP classement');
        $this->addSql('ALTER TABLE societe DROP last_login, DROP classement');
    }
}
