<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110204225 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD isha_fixation SMALLINT DEFAULT NULL');
        $this->addSql('update configuration set isha_fixation = 90 where ninety_min_between_maghib_and_isha = 1');
        $this->addSql('ALTER TABLE configuration  DROP ninety_min_between_maghib_and_isha');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD ninety_min_between_maghib_and_isha TINYINT(1) NOT NULL');
        $this->addSql('update configuration set ninety_min_between_maghib_and_isha = 1 where isha_fixation = 90');
        $this->addSql('ALTER TABLE configuration DROP isha_fixation');
    }
}
