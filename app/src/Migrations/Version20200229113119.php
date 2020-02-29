<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200229113119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change relation between comment and bug to use UUID.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('ALTER TABLE comment ADD bug_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN comment.bug_id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'ALTER TABLE comment 
    ADD CONSTRAINT FK_9474526CFA3DB3D5 
    FOREIGN KEY (bug_id) REFERENCES bug (id) 
    NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql('CREATE INDEX IDX_9474526CFA3DB3D5 ON comment (bug_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CFA3DB3D5');
        $this->addSql('DROP INDEX IDX_9474526CFA3DB3D5');
        $this->addSql('ALTER TABLE comment DROP bug_id');
    }
}
