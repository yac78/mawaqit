<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926095929 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX uniq_69348fe8a90aba9 ON parameters');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_69348FE46C40146 ON parameters (key_param)');
        $this->addSql('ALTER TABLE mosque ADD email_screen_photo_reminder SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque DROP email_screen_photo_reminder');
        $this->addSql('DROP INDEX uniq_69348fe46c40146 ON parameters');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_69348FE8A90ABA9 ON parameters (key_param)');
    }
}
