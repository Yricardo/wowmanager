<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526034453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__invitation AS SELECT id, invited_by_id, status, username, secret_tag, email, created_at FROM invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, invited_by_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, secret_tag VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , CONSTRAINT FK_F11D61A2A7B4A7E3 FOREIGN KEY (invited_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO invitation (id, invited_by_id, status, username, secret_tag, email, created_at) SELECT id, invited_by_id, status, username, secret_tag, email, created_at FROM __temp__invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__invitation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F11D61A2A7B4A7E3 ON invitation (invited_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD COLUMN content CLOB NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__invitation AS SELECT id, invited_by_id, status, username, secret_tag, email, created_at FROM invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, invited_by_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, secret_tag VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_F11D61A2A7B4A7E3 FOREIGN KEY (invited_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO invitation (id, invited_by_id, status, username, secret_tag, email, created_at) SELECT id, invited_by_id, status, username, secret_tag, email, created_at FROM __temp__invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__invitation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F11D61A2A7B4A7E3 ON invitation (invited_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__message AS SELECT id, sender_id, receiver_id, read, created_at, is_visible FROM message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender_id INTEGER DEFAULT NULL, receiver_id INTEGER DEFAULT NULL, read BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , is_visible BOOLEAN NOT NULL, CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO message (id, sender_id, receiver_id, read, created_at, is_visible) SELECT id, sender_id, receiver_id, read, created_at, is_visible FROM __temp__message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FCD53EDB6 ON message (receiver_id)
        SQL);
    }
}
