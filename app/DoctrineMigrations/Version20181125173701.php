<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181125173701 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque DROP FOREIGN KEY FK_5DE348CAE2A4756F');
        $this->addSql('ALTER TABLE mosque ADD CONSTRAINT FK_5DE348CAE2A4756F FOREIGN KEY (flash_message_id) REFERENCES flash_message (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque DROP FOREIGN KEY FK_5DE348CAE2A4756F');
        $this->addSql('ALTER TABLE mosque ADD CONSTRAINT FK_5DE348CAE2A4756F FOREIGN KEY (flash_message_id) REFERENCES flash_message (id)');
    }
}
