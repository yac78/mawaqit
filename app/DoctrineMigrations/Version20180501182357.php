<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180501182357 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('update mosque set country =\'FR\' where country = \'france\'');
        $this->addSql('update mosque set country =\'DZ\' where country = \'algerie\'');
        $this->addSql('update mosque set country =\'IT\' where country = \'ITALIA\'');
        $this->addSql('update mosque set country =\'BE\' where country = \'BELGIQUE\'');
        $this->addSql('update mosque set country =\'MA\' where country = \'MAROC\'');
        $this->addSql('update mosque set country =\'US\' where country = \'USA\'');
        $this->addSql('update mosque set country =\'TN\' where country = \'TUNISIE\'');
        $this->addSql('update mosque set country =\'CA\' where country = \'canada\'');
        $this->addSql('update mosque set country =\'DE\' where country = \'DEUTSCHLAND\'');
        $this->addSql('update mosque set country =\'CH\' where country = \'SUISSE\'');
        $this->addSql('update mosque set country =\'AT\' where country = \'AUSTRIA\'');
        $this->addSql('update mosque set country =\'SN\' where country = \'SENEGAL\'');
        $this->addSql('update mosque set country =\'GB\' where country = \'UNITED-KINGDOM\'');
        $this->addSql('update mosque set country =\'NL\' where country = \'NEDERLAND\'');
        $this->addSql('update mosque set country =\'LU\' where country = \'LUXEMBOURG\'');
        $this->addSql('update mosque set country =\'YT\' where country = \'MAYOTTE\'');
        $this->addSql('update mosque set country =\'ES\' where country = \'ESPAÑA\'  ');
        $this->addSql('update mosque set country =\'PT\' where country = \'PORTUGAL\'');
        $this->addSql('update mosque set country =\'ML\' where country = \'MALI\'');
        $this->addSql('update mosque set country =\'ID\' where country = \'INDONESIA\'');
        $this->addSql('update mosque set country =\'ZA\' where country = \'SOUTH-AFRICA\'');
        $this->addSql('update mosque set country =\'NL\' where country = \'PAYS-BAS\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD created DATETIME NOT NULL');
    }
}
