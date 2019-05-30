<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190530163826 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD timezone_name VARCHAR(255) NOT NULL');
        $this->addSql('update configuration set timezone_name = \'Europe/Paris\' where id in (select configuration_id from mosque where country = \'FR\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Algiers\' where id in (select configuration_id from mosque where country = \'DZ\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Casablanca\' where id in (select configuration_id from mosque where country = \'MA\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Brussels\' where id in (select configuration_id from mosque where country = \'BE\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Tunis\' where id in (select configuration_id from mosque where country = \'TN\')');
        $this->addSql('update configuration set timezone_name = \'America/Winnipeg\' where id in (select configuration_id from mosque where country = \'CA\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Berlin\' where id in (select configuration_id from mosque where country = \'DE\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Zurich\' where id in (select configuration_id from mosque where country = \'CH\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Vienna\' where id in (select configuration_id from mosque where country = \'AT\')');
        $this->addSql('update configuration set timezone_name = \'Europe/London\' where id in (select configuration_id from mosque where country = \'GB\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Amsterdam\' where id in (select configuration_id from mosque where country = \'NL\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Luxembourg\' where id in (select configuration_id from mosque where country = \'LU\')');
        $this->addSql('update configuration set timezone_name = \'Indian/Mayotte\' where id in (select configuration_id from mosque where country = \'YT\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Madrid\' where id in (select configuration_id from mosque where country = \'ES\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Lisbon\' where id in (select configuration_id from mosque where country = \'PT\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Rome\' where id in (select configuration_id from mosque where country = \'IT\')');
        $this->addSql('update configuration set timezone_name = \'America/Toronto\' where id in (select configuration_id from mosque where country = \'US\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Jakarta\' where id in (select configuration_id from mosque where country = \'ID\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Dakar\' where id in (select configuration_id from mosque where country = \'SN\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Johannesburg\' where id in (select configuration_id from mosque where country = \'ZA\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Abidjan\' where id in (select configuration_id from mosque where country = \'CI\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Vientiane\' where id in (select configuration_id from mosque where country = \'LA\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Kolkata\' where id in (select configuration_id from mosque where country = \'IN\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Muscat\' where id in (select configuration_id from mosque where country = \'OM\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Porto-Novo\' where id in (select configuration_id from mosque where country = \'BJ\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Nouakchott\' where id in (select configuration_id from mosque where country = \'MR\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Lagos\' where id in (select configuration_id from mosque where country = \'CM\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Istanbul\' where id in (select configuration_id from mosque where country = \'TR\')');
        $this->addSql('update configuration set timezone_name = \'Africa/Djibouti\' where id in (select configuration_id from mosque where country = \'DJ\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Stockholm\' where id in (select configuration_id from mosque where country = \'SE\')');
        $this->addSql('update configuration set timezone_name = \'America/Curacao\' where id in (select configuration_id from mosque where country = \'BQ\')');
        $this->addSql('update configuration set timezone_name = \'Indian/Comoro\' where id in (select configuration_id from mosque where country = \'KM\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Minsk\' where id in (select configuration_id from mosque where country = \'BY\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Kuala_Lumpur\' where id in (select configuration_id from mosque where country = \'MY\')');
        $this->addSql('update configuration set timezone_name = \'Europe/Copenhagen\' where id in (select configuration_id from mosque where country = \'DK\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Yakutsk\' where id in (select configuration_id from mosque where country = \'RU\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Riyadh\' where id in (select configuration_id from mosque where country = \'SA\')');
        $this->addSql('update configuration set timezone_name = \'Asia/Baku\' where id in (select configuration_id from mosque where country = \'AZ\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration DROP timezone_name');
    }
}
