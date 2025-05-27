<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524023049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE invitation ADD COLUMN created_at DATETIME NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__invitation AS SELECT id, invited_by_id, status, username, secret_tag, email FROM invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, invited_by_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, secret_tag VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, CONSTRAINT FK_F11D61A2A7B4A7E3 FOREIGN KEY (invited_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO invitation (id, invited_by_id, status, username, secret_tag, email) SELECT id, invited_by_id, status, username, secret_tag, email FROM __temp__invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__invitation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F11D61A2A7B4A7E3 ON invitation (invited_by_id)
        SQL);
    }
}
