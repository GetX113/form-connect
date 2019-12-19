<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191206193628 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE disponibilite ADD formateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('CREATE INDEX IDX_2CBACE2F155D8F51 ON disponibilite (formateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F155D8F51');
        $this->addSql('DROP INDEX IDX_2CBACE2F155D8F51 ON disponibilite');
        $this->addSql('ALTER TABLE disponibilite DROP formateur_id');
    }
}
