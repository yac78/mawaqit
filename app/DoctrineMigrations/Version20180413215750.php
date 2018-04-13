<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180413215750 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD footer TINYINT(1) NOT NULL');
        $this->addSql('UPDATE configuration set footer = 0 where hide_footer = 1');
        $this->addSql('UPDATE configuration set footer = 1 where hide_footer = 0');
        $this->addSql('ALTER TABLE configuration DROP hide_footer');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD hide_footer TINYINT(1) NOT NULL');
        $this->addSql('UPDATE configuration set hide_footer = 0 where footer = 1');
        $this->addSql('UPDATE configuration set hide_footer = 1 where footer = 0');
        $this->addSql('ALTER TABLE configuration DROP footer');
    }
}
