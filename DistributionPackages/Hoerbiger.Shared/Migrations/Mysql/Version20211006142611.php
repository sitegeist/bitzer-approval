<?php declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to extend the media asset title property to TEXT
 */
class Version20211006142611 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Extend the Asset title property to TEXT';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".'
        );
        $this->addSql("ALTER TABLE neos_media_domain_model_asset CHANGE title title TEXT NOT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".'
        );

        $this->addSql("ALTER TABLE neos_media_domain_model_asset CHANGE title title VARCHAR(255) NOT NULL");
    }
}
