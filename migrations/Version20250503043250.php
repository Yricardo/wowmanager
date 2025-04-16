<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503043250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE auction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, item_id INTEGER NOT NULL, price_id INTEGER NOT NULL, seller_id INTEGER NOT NULL, minimum_buy_price_id INTEGER NOT NULL, buyout_price_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, sold_quantity INTEGER NOT NULL, buyout BOOLEAN NOT NULL, creation_date DATETIME NOT NULL, duration_hour INTEGER NOT NULL, status VARCHAR(255) NOT NULL, visibility VARCHAR(255) NOT NULL, open_for_bid BOOLEAN NOT NULL, CONSTRAINT FK_DEE4F593126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DEE4F593D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DEE4F5938DE820D9 FOREIGN KEY (seller_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DEE4F593806AE144 FOREIGN KEY (minimum_buy_price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DEE4F5935A0AE2D2 FOREIGN KEY (buyout_price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DEE4F593126F525E ON auction (item_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DEE4F593D614C7E7 ON auction (price_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DEE4F5938DE820D9 ON auction (seller_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DEE4F593806AE144 ON auction (minimum_buy_price_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DEE4F5935A0AE2D2 ON auction (buyout_price_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE auction_bid (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bidder_id INTEGER DEFAULT NULL, price_id INTEGER NOT NULL, auction_id INTEGER DEFAULT NULL, CONSTRAINT FK_401A9C43BE40AFAE FOREIGN KEY (bidder_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_401A9C43D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_401A9C4357B8F0DE FOREIGN KEY (auction_id) REFERENCES auction (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_401A9C43BE40AFAE ON auction_bid (bidder_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_401A9C43D614C7E7 ON auction_bid (price_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_401A9C4357B8F0DE ON auction_bid (auction_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE available_role_for_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_id INTEGER NOT NULL, role_id INTEGER DEFAULT NULL, CONSTRAINT FK_AD84205DEA000B10 FOREIGN KEY (class_id) REFERENCES character_class (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AD84205DD60322AC FOREIGN KEY (role_id) REFERENCES character_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AD84205DEA000B10 ON available_role_for_class (class_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AD84205DD60322AC ON available_role_for_class (role_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, server_id INTEGER NOT NULL, character_class_id INTEGER NOT NULL, chosen_role_id INTEGER DEFAULT NULL, race_id INTEGER DEFAULT NULL, user_id INTEGER NOT NULL, guild_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, level SMALLINT NOT NULL, gear_level INTEGER DEFAULT NULL, CONSTRAINT FK_937AB0341844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB034B201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB0342CF79437 FOREIGN KEY (chosen_role_id) REFERENCES character_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB0346E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB0345F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB0341844E6B7 ON character (server_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB034B201E281 ON character (character_class_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB0342CF79437 ON character (chosen_role_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB0346E59D40D ON character (race_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB034A76ED395 ON character (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB0345F2131EF ON character (guild_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, class_img_path VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character_role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, img_path VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE friend_link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user1_id INTEGER NOT NULL, user2_id INTEGER NOT NULL, CONSTRAINT FK_ACD6451156AE248B FOREIGN KEY (user1_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_ACD64511441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ACD6451156AE248B ON friend_link (user1_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ACD64511441B8B65 ON friend_link (user2_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE guild (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, server_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_75407DAB1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75407DAB1844E6B7 ON guild (server_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, id_wow INTEGER NOT NULL, img_path VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender_id INTEGER DEFAULT NULL, receiver_id INTEGER DEFAULT NULL, read BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , is_visible BOOLEAN NOT NULL, CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FCD53EDB6 ON message (receiver_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, link VARCHAR(300) DEFAULT NULL, description CLOB NOT NULL, read BOOLEAN NOT NULL, CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE owned_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, item_id INTEGER NOT NULL, character_id INTEGER NOT NULL, CONSTRAINT FK_5C32A708126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C32A7081136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C32A708126F525E ON owned_item (item_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C32A7081136BE75 ON owned_item (character_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE price (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gold INTEGER NOT NULL, silver INTEGER NOT NULL, bronze INTEGER NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE race (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE server (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, wow_version_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_5A6DD5F6643861C3 FOREIGN KEY (wow_version_id) REFERENCES wow_version (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A6DD5F6643861C3 ON server (wow_version_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE wow_version (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE auction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE auction_bid
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE available_role_for_class
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character_class
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE friend_link
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE guild
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE owned_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE price
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE race
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE server
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE wow_version
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
