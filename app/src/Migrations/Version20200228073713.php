<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200228073713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Bug table with its fields and relations';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE comment (
    id INT NOT NULL, 
    author_id INT NOT NULL, 
    content TEXT NOT NULL, 
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    PRIMARY KEY(id))',
        );
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql(
            'CREATE TABLE bug (
    id UUID NOT NULL, 
    project_id INT NOT NULL, 
    reporter_id INT NOT NULL, 
    assignee_id INT NOT NULL, 
    bug_id INT NOT NULL, 
    status SMALLINT NOT NULL, 
    priority SMALLINT NOT NULL, 
    due DATE NOT NULL, 
    title VARCHAR(128) NOT NULL, 
    summary TEXT NOT NULL, 
    reproduce TEXT NOT NULL, 
    expected TEXT NOT NULL, 
    actual TEXT NOT NULL, 
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    PRIMARY KEY(id))',
        );
        $this->addSql('CREATE INDEX IDX_358CBF14166D1F9C ON bug (project_id)');
        $this->addSql('CREATE INDEX IDX_358CBF14E1CFE6F5 ON bug (reporter_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_358CBF1459EC7D60 ON bug (assignee_id)');
        $this->addSql('COMMENT ON COLUMN bug.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN bug.due IS \'(DC2Type:date_immutable)\'');
        $this->addSql(
            'CREATE TABLE project (
    id INT NOT NULL, 
    project_id VARCHAR(5) NOT NULL, 
    name VARCHAR(128) NOT NULL, 
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    PRIMARY KEY(id))',
        );
        $this->addSql(
            'ALTER TABLE comment 
    ADD CONSTRAINT FK_9474526CF675F31B 
    FOREIGN KEY (author_id) 
    REFERENCES "user" (id) 
    NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE bug 
    ADD CONSTRAINT FK_358CBF14166D1F9C 
    FOREIGN KEY (project_id) 
    REFERENCES project (id) 
    NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE bug 
    ADD CONSTRAINT FK_358CBF14E1CFE6F5 
    FOREIGN KEY (reporter_id) 
    REFERENCES "user" (id) 
    NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE bug 
    ADD CONSTRAINT FK_358CBF1459EC7D60 
    FOREIGN KEY (assignee_id) 
    REFERENCES "user" (id) 
    NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE "user" 
    ADD created_at 
    TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL',
        );
        $this->addSql(
            'ALTER TABLE "user" 
    ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL',
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('ALTER TABLE bug DROP CONSTRAINT FK_358CBF14166D1F9C');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE bug');
        $this->addSql('DROP TABLE project');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" DROP updated_at');
    }
}
