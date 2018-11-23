<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181123174809 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque ADD women_space TINYINT(1) DEFAULT NULL, ADD funeral_toilet TINYINT(1) DEFAULT NULL, ADD children_courses TINYINT(1) DEFAULT NULL, ADD adult_courses TINYINT(1) DEFAULT NULL, ADD ramadan_meal TINYINT(1) DEFAULT NULL, ADD handicap_accessibility TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mosque DROP women_space, DROP funeral_toilet, DROP children_courses, DROP adult_courses, DROP ramadan_meal, DROP handicap_accessibility');
    }
}
