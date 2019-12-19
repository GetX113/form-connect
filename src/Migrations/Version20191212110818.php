<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191212110818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formation_formateur CHANGE date date VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation_formateur ADD CONSTRAINT FK_270B2E92155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('CREATE INDEX IDX_270B2E92155D8F51 ON formation_formateur (formateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formation_formateur DROP FOREIGN KEY FK_270B2E92155D8F51');
        $this->addSql('DROP INDEX IDX_270B2E92155D8F51 ON formation_formateur');
        $this->addSql('ALTER TABLE formation_formateur CHANGE date date DATE DEFAULT NULL');
    }
}
