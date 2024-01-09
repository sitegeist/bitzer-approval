<?php declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to extend the media asset title property to TEXT
 */
class Version20231213163840 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Removes duplications of resource records which are not used anywhere';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".'
        );
        $this->addSql("DELETE FROM `neos_flow_resourcemanagement_persistentresource`
            WHERE `persistence_object_identifier` NOT IN (SELECT `resource` FROM `neos_media_domain_model_asset` WHERE `resource` IS NOT NULL)
            AND `persistence_object_identifier` NOT IN (SELECT `resource` FROM `neos_media_domain_model_thumbnail` WHERE `resource` IS NOT NULL)"
            );

    }

    public function down(Schema $schema): void
    {
    }
}
