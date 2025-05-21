<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521200407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, invited_by_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, secret_tag VARCHAR(255) NOT NULL, CONSTRAINT FK_F11D61A2A7B4A7E3 FOREIGN KEY (invited_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F11D61A2A7B4A7E3 ON invitation (invited_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE setting
        SQL);
    }
}
