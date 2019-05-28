<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190528123920 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE configuration SET prayer_method = 'FRANCE' WHERE prayer_method = 'UOIF'");
        $this->addSql("UPDATE configuration SET prayer_method = 'KARACHI' WHERE prayer_method = 'Karachi'");
        $this->addSql("UPDATE configuration SET prayer_method = 'MAKKAH' WHERE prayer_method = 'Makkah'");
        $this->addSql("UPDATE configuration SET prayer_method = 'EGYPT' WHERE prayer_method = 'Egypt'");

        $this->addSql("UPDATE configuration SET asr_method = 'STANDARD' WHERE asr_method = 'Standard'");
        $this->addSql("UPDATE configuration SET asr_method = 'HANAFI' WHERE asr_method = 'Hanafi'");

        $this->addSql("UPDATE configuration SET high_lats_method = 'ANGLE_BASED' WHERE high_lats_method = 'AngleBased' OR high_lats_method  IS NULL");
        $this->addSql("UPDATE configuration SET high_lats_method = 'MIDDLE_OF_THE_NIGHT' WHERE high_lats_method = 'NightMiddle'");
        $this->addSql("UPDATE configuration SET high_lats_method = 'ONE_SEVENTH' WHERE high_lats_method = 'OneSeventh'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
