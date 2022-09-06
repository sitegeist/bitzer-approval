<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to add tables for marketing URLs
 */
class Version20220906153748 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Creates tables for marketing URLs';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".'
        );

        $this->addSql('CREATE TABLE `hoerbiger_hoerbigercom_marketingurl` (
                `path` VARCHAR(255) NOT NULL,
                `node_aggregate_identifier` VARCHAR(40) NOT NULL,
                PRIMARY KEY(`path`))
                DEFAULT CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci
                ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".'
        );

        $this->addSql('DROP TABLE hoerbiger_hoerbigercom_marketingurl');
    }
}
