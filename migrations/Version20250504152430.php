<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504152430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE event_log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, description CLOB DEFAULT NULL, recorded_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , CONSTRAINT FK_9EF0AD16C54C8C93 FOREIGN KEY (type_id) REFERENCES event_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9EF0AD16C54C8C93 ON event_log (type_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_log_user (event_log_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(event_log_id, user_id), CONSTRAINT FK_DA11D1ECD8FE2AD4 FOREIGN KEY (event_log_id) REFERENCES event_log (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DA11D1ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA11D1ECD8FE2AD4 ON event_log_user (event_log_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA11D1ECA76ED395 ON event_log_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE event_log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_log_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_type
        SQL);
    }
}
