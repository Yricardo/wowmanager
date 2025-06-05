<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602113151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE auction (id INT AUTO_INCREMENT NOT NULL, item_id INT NOT NULL, price_id INT NOT NULL, seller_id INT NOT NULL, minimum_buy_price_id INT NOT NULL, buyout_price_id INT DEFAULT NULL, quantity INT NOT NULL, sold_quantity INT NOT NULL, buyout TINYINT(1) NOT NULL, creation_date DATETIME NOT NULL, duration_hour INT NOT NULL, status VARCHAR(255) NOT NULL, visibility VARCHAR(255) NOT NULL, open_for_bid TINYINT(1) NOT NULL, INDEX IDX_DEE4F593126F525E (item_id), UNIQUE INDEX UNIQ_DEE4F593D614C7E7 (price_id), INDEX IDX_DEE4F5938DE820D9 (seller_id), UNIQUE INDEX UNIQ_DEE4F593806AE144 (minimum_buy_price_id), UNIQUE INDEX UNIQ_DEE4F5935A0AE2D2 (buyout_price_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE auction_bid (id INT AUTO_INCREMENT NOT NULL, bidder_id INT DEFAULT NULL, price_id INT NOT NULL, auction_id INT DEFAULT NULL, INDEX IDX_401A9C43BE40AFAE (bidder_id), UNIQUE INDEX UNIQ_401A9C43D614C7E7 (price_id), INDEX IDX_401A9C4357B8F0DE (auction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE available_role_for_class (id INT AUTO_INCREMENT NOT NULL, class_id INT NOT NULL, role_id INT DEFAULT NULL, INDEX IDX_AD84205DEA000B10 (class_id), INDEX IDX_AD84205DD60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character_class (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, class_img_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, img_path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_log (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, description LONGTEXT DEFAULT NULL, recorded_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_9EF0AD16C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_log_user (event_log_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DA11D1ECD8FE2AD4 (event_log_id), INDEX IDX_DA11D1ECA76ED395 (user_id), PRIMARY KEY(event_log_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE friend_link (id INT AUTO_INCREMENT NOT NULL, user1_id INT NOT NULL, user2_id INT NOT NULL, INDEX IDX_ACD6451156AE248B (user1_id), INDEX IDX_ACD64511441B8B65 (user2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE game_character (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, character_class_id INT NOT NULL, chosen_role_id INT DEFAULT NULL, race_id INT DEFAULT NULL, user_id INT NOT NULL, guild_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, level SMALLINT NOT NULL, gear_level INT DEFAULT NULL, INDEX IDX_41DC71361844E6B7 (server_id), INDEX IDX_41DC7136B201E281 (character_class_id), INDEX IDX_41DC71362CF79437 (chosen_role_id), INDEX IDX_41DC71366E59D40D (race_id), INDEX IDX_41DC7136A76ED395 (user_id), INDEX IDX_41DC71365F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE guild (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_75407DAB1844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, invited_by_id INT NOT NULL, status VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, secret_tag VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_F11D61A2A7B4A7E3 (invited_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, id_wow INT NOT NULL, img_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', is_visible TINYINT(1) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, link VARCHAR(300) DEFAULT NULL, description LONGTEXT NOT NULL, `read` TINYINT(1) NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE owned_item (id INT AUTO_INCREMENT NOT NULL, item_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_5C32A708126F525E (item_id), INDEX IDX_5C32A7081136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, gold INT NOT NULL, silver INT NOT NULL, bronze INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, wow_version_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5A6DD5F6643861C3 (wow_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, related_entity VARCHAR(255) DEFAULT NULL, is_global TINYINT(1) NOT NULL, INDEX IDX_9F74B898A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', trust_score INT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE wow_version (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction ADD CONSTRAINT FK_DEE4F593126F525E FOREIGN KEY (item_id) REFERENCES item (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction ADD CONSTRAINT FK_DEE4F593D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5938DE820D9 FOREIGN KEY (seller_id) REFERENCES game_character (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction ADD CONSTRAINT FK_DEE4F593806AE144 FOREIGN KEY (minimum_buy_price_id) REFERENCES price (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5935A0AE2D2 FOREIGN KEY (buyout_price_id) REFERENCES price (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction_bid ADD CONSTRAINT FK_401A9C43BE40AFAE FOREIGN KEY (bidder_id) REFERENCES game_character (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction_bid ADD CONSTRAINT FK_401A9C43D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction_bid ADD CONSTRAINT FK_401A9C4357B8F0DE FOREIGN KEY (auction_id) REFERENCES auction (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE available_role_for_class ADD CONSTRAINT FK_AD84205DEA000B10 FOREIGN KEY (class_id) REFERENCES character_class (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE available_role_for_class ADD CONSTRAINT FK_AD84205DD60322AC FOREIGN KEY (role_id) REFERENCES character_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_log ADD CONSTRAINT FK_9EF0AD16C54C8C93 FOREIGN KEY (type_id) REFERENCES event_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_log_user ADD CONSTRAINT FK_DA11D1ECD8FE2AD4 FOREIGN KEY (event_log_id) REFERENCES event_log (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_log_user ADD CONSTRAINT FK_DA11D1ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friend_link ADD CONSTRAINT FK_ACD6451156AE248B FOREIGN KEY (user1_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friend_link ADD CONSTRAINT FK_ACD64511441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character ADD CONSTRAINT FK_41DC71361844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character ADD CONSTRAINT FK_41DC7136B201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character ADD CONSTRAINT FK_41DC71362CF79437 FOREIGN KEY (chosen_role_id) REFERENCES character_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character ADD CONSTRAINT FK_41DC71366E59D40D FOREIGN KEY (race_id) REFERENCES race (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character ADD CONSTRAINT FK_41DC7136A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character ADD CONSTRAINT FK_41DC71365F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE guild ADD CONSTRAINT FK_75407DAB1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2A7B4A7E3 FOREIGN KEY (invited_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE owned_item ADD CONSTRAINT FK_5C32A708126F525E FOREIGN KEY (item_id) REFERENCES item (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE owned_item ADD CONSTRAINT FK_5C32A7081136BE75 FOREIGN KEY (character_id) REFERENCES game_character (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6643861C3 FOREIGN KEY (wow_version_id) REFERENCES wow_version (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE setting ADD CONSTRAINT FK_9F74B898A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F593126F525E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F593D614C7E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5938DE820D9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F593806AE144
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5935A0AE2D2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction_bid DROP FOREIGN KEY FK_401A9C43BE40AFAE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction_bid DROP FOREIGN KEY FK_401A9C43D614C7E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auction_bid DROP FOREIGN KEY FK_401A9C4357B8F0DE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE available_role_for_class DROP FOREIGN KEY FK_AD84205DEA000B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE available_role_for_class DROP FOREIGN KEY FK_AD84205DD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_log DROP FOREIGN KEY FK_9EF0AD16C54C8C93
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_log_user DROP FOREIGN KEY FK_DA11D1ECD8FE2AD4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_log_user DROP FOREIGN KEY FK_DA11D1ECA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friend_link DROP FOREIGN KEY FK_ACD6451156AE248B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friend_link DROP FOREIGN KEY FK_ACD64511441B8B65
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character DROP FOREIGN KEY FK_41DC71361844E6B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character DROP FOREIGN KEY FK_41DC7136B201E281
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character DROP FOREIGN KEY FK_41DC71362CF79437
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character DROP FOREIGN KEY FK_41DC71366E59D40D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character DROP FOREIGN KEY FK_41DC7136A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_character DROP FOREIGN KEY FK_41DC71365F2131EF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB1844E6B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2A7B4A7E3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE owned_item DROP FOREIGN KEY FK_5C32A708126F525E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE owned_item DROP FOREIGN KEY FK_5C32A7081136BE75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6643861C3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE setting DROP FOREIGN KEY FK_9F74B898A76ED395
        SQL);
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
            DROP TABLE character_class
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_log_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE friend_link
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game_character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE guild
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
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
            DROP TABLE setting
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
