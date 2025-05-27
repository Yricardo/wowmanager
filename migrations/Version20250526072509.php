<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526072509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__setting AS SELECT id, name, type, value, related_entity FROM setting
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE setting
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, related_entity VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_9F74B898A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO setting (id, name, type, value, related_entity) SELECT id, name, type, value, related_entity FROM __temp__setting
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__setting
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9F74B898A76ED395 ON setting (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__setting AS SELECT id, name, type, value, related_entity FROM setting
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE setting
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, related_entity VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO setting (id, name, type, value, related_entity) SELECT id, name, type, value, related_entity FROM __temp__setting
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__setting
        SQL);
    }
}
