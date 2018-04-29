<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429093932 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque ADD configuration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mosque ADD CONSTRAINT FK_5DE348CA73F32DD8 FOREIGN KEY (configuration_id) REFERENCES configuration (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5DE348CA73F32DD8 ON mosque (configuration_id)');
        $this->addSql('ALTER TABLE configuration DROP FOREIGN KEY FK_A5E2A5D7FBDAA034');
        $this->addSql('DROP INDEX UNIQ_A5E2A5D7FBDAA034 ON configuration');
        $this->addSql('ALTER TABLE configuration DROP mosque_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD mosque_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE configuration ADD CONSTRAINT FK_A5E2A5D7FBDAA034 FOREIGN KEY (mosque_id) REFERENCES mosque (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A5E2A5D7FBDAA034 ON configuration (mosque_id)');
        $this->addSql('ALTER TABLE mosque DROP FOREIGN KEY FK_5DE348CA73F32DD8');
        $this->addSql('DROP INDEX UNIQ_5DE348CA73F32DD8 ON mosque');
        $this->addSql('ALTER TABLE mosque DROP configuration_id');
    }
}
