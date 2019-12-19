<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191206194023 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE date_disponibilite (id INT AUTO_INCREMENT NOT NULL, date VARCHAR(4068) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disponibilite DROP date_disponibilite');
        $this->addSql('ALTER TABLE formateur ADD date_disponibilites_id INT NOT NULL');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4FBB6144A6 FOREIGN KEY (date_disponibilites_id) REFERENCES date_disponibilite (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ED767E4FBB6144A6 ON formateur (date_disponibilites_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formateur DROP FOREIGN KEY FK_ED767E4FBB6144A6');
        $this->addSql('DROP TABLE date_disponibilite');
        $this->addSql('ALTER TABLE disponibilite ADD date_disponibilite VARCHAR(4064) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_ED767E4FBB6144A6 ON formateur');
        $this->addSql('ALTER TABLE formateur DROP date_disponibilites_id');
    }
}
