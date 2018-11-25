<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181125155125 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE flash_message (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT DEFAULT NULL, expire DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mosque ADD flash_message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mosque ADD CONSTRAINT FK_5DE348CAE2A4756F FOREIGN KEY (flash_message_id) REFERENCES flash_message (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5DE348CAE2A4756F ON mosque (flash_message_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque DROP FOREIGN KEY FK_5DE348CAE2A4756F');
        $this->addSql('DROP TABLE flash_message');
        $this->addSql('DROP INDEX UNIQ_5DE348CAE2A4756F ON mosque');
        $this->addSql('ALTER TABLE mosque DROP flash_message_id');
    }
}
