<?php
declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * The migration for providing the approval assignment table structure
 */
class Version20211011221922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'The migration for providing the approval assignment table structure';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE sitegeist_bitzer_approval_domain_approval_approvalassignment (workspace_name VARCHAR(255) NOT NULL, responsible_agent_identifier VARCHAR(255) NOT NULL, PRIMARY KEY(workspace_name, responsible_agent_identifier)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on "mysql".');

        $this->addSql('DROP TABLE sitegeist_bitzer_approval_domain_approval_approvalassignment');
    }
}
