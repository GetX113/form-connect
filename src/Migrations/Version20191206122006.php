<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191206122006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formateur ADD information_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4F2EF03101 FOREIGN KEY (information_id) REFERENCES formation_formateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ED767E4F2EF03101 ON formateur (information_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formateur DROP FOREIGN KEY FK_ED767E4F2EF03101');
        $this->addSql('DROP INDEX UNIQ_ED767E4F2EF03101 ON formateur');
        $this->addSql('ALTER TABLE formateur DROP information_id');
    }
}
