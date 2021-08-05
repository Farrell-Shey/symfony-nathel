<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210805074636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announce (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ban (id INT AUTO_INCREMENT NOT NULL, confrontation_id INT NOT NULL, mappool_map_id INT DEFAULT NULL, is_blue_side TINYINT(1) NOT NULL, INDEX IDX_62FED0E518EE86FA (confrontation_id), INDEX IDX_62FED0E5F079958C (mappool_map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beatmap (id INT AUTO_INCREMENT NOT NULL, difficulty VARCHAR(255) NOT NULL, bpm INT NOT NULL, ar INT NOT NULL, cs INT NOT NULL, drain INT NOT NULL, accuracy INT NOT NULL, hit_length INT NOT NULL, mode_int VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beatmapset (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, creator VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blacklisted (id INT AUTO_INCREMENT NOT NULL, reason VARCHAR(255) NOT NULL, is_admin_approved TINYINT(1) DEFAULT NULL, is_ban_all_over TINYINT(1) DEFAULT NULL, severity VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, addlike INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE confrontation (id INT AUTO_INCREMENT NOT NULL, tourney_id INT NOT NULL, step_id INT NOT NULL, ref_id INT DEFAULT NULL, first_date DATETIME DEFAULT NULL, final_date DATETIME NOT NULL, red_side INT DEFAULT NULL, blue_side INT DEFAULT NULL, status VARCHAR(255) NOT NULL, is_first_picker TINYINT(1) NOT NULL, position INT DEFAULT NULL, INDEX IDX_EB1249E7ECAE3834 (tourney_id), INDEX IDX_EB1249E773B21E9C (step_id), INDEX IDX_EB1249E721B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, tourney_id INT NOT NULL, size INT DEFAULT NULL, INDEX IDX_6DC044C5ECAE3834 (tourney_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_confrontation (group_id INT NOT NULL, confrontation_id INT NOT NULL, INDEX IDX_B4FB87A5FE54D947 (group_id), INDEX IDX_B4FB87A518EE86FA (confrontation_id), PRIMARY KEY(group_id, confrontation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_player (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, pool_id INT NOT NULL, ranking INT DEFAULT NULL, INDEX IDX_4B9FA2B599E6F5DF (player_id), INDEX IDX_4B9FA2B57B3406DF (pool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, is_accept TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lobbie (id INT AUTO_INCREMENT NOT NULL, tourney_id INT NOT NULL, step_id INT DEFAULT NULL, date DATETIME NOT NULL, is_replay TINYINT(1) NOT NULL, INDEX IDX_860DEBE1ECAE3834 (tourney_id), INDEX IDX_860DEBE173B21E9C (step_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mappool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, follow INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mappool_map (id INT AUTO_INCREMENT NOT NULL, mappool_id INT NOT NULL, beatmap_id INT NOT NULL, user_id INT NOT NULL, mode VARCHAR(255) NOT NULL, INDEX IDX_B50D204AC6833A60 (mappool_id), INDEX IDX_B50D204AC60DD20A (beatmap_id), INDEX IDX_B50D204AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tourney_id INT NOT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_98197A65A76ED395 (user_id), INDEX IDX_98197A65ECAE3834 (tourney_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pool_set (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, mappool_map_id INT NOT NULL, encounter INT NOT NULL, score INT NOT NULL, accuracy INT NOT NULL, misscount INT NOT NULL, is_v1 TINYINT(1) NOT NULL, INDEX IDX_C5EEEA3499E6F5DF (player_id), INDEX IDX_C5EEEA34F079958C (mappool_map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, step VARCHAR(255) NOT NULL, date DATETIME NOT NULL, position INT NOT NULL, best_of INT DEFAULT NULL, bans INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, team_name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_user (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, team_id INT NOT NULL, is_capitain TINYINT(1) NOT NULL, INDEX IDX_5C72223299E6F5DF (player_id), INDEX IDX_5C722232296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tourney (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, iteration INT NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, background_home VARCHAR(255) DEFAULT NULL, follow INT NOT NULL, nb_player INT NOT NULL, nb_inscrits VARCHAR(255) NOT NULL, discord VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, forum_post VARCHAR(255) DEFAULT NULL, mode VARCHAR(255) NOT NULL, is_scorev2 TINYINT(1) NOT NULL, is_scale VARCHAR(255) DEFAULT NULL, is_team TINYINT(1) NOT NULL, is_qualif TINYINT(1) NOT NULL, groupstages TINYINT(1) NOT NULL, bracket_format VARCHAR(255) NOT NULL, max_pt INT DEFAULT NULL, max_reg INT DEFAULT NULL, round_of INT DEFAULT NULL, reg_start_date DATETIME NOT NULL, reg_close_date DATETIME NOT NULL, color_theme VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tourney_staff (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tourney_id INT NOT NULL, role VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_5EF8D741A76ED395 (user_id), INDEX IDX_5EF8D741ECAE3834 (tourney_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, osu_id INT NOT NULL, name VARCHAR(255) NOT NULL, token VARCHAR(1024) NOT NULL, discord VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, cover VARCHAR(255) DEFAULT NULL, timezone INT DEFAULT NULL, country VARCHAR(255) NOT NULL, silver_ss INT DEFAULT NULL, count_ss INT DEFAULT NULL, count_silver_s INT DEFAULT NULL, count_s INT DEFAULT NULL, count_a INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, game_mode_std INT DEFAULT NULL, game_mode_mania INT DEFAULT NULL, game_mode_taiko INT DEFAULT NULL, game_mode_ctb INT DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, twitch VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, page VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ban ADD CONSTRAINT FK_62FED0E518EE86FA FOREIGN KEY (confrontation_id) REFERENCES confrontation (id)');
        $this->addSql('ALTER TABLE ban ADD CONSTRAINT FK_62FED0E5F079958C FOREIGN KEY (mappool_map_id) REFERENCES mappool_map (id)');
        $this->addSql('ALTER TABLE confrontation ADD CONSTRAINT FK_EB1249E7ECAE3834 FOREIGN KEY (tourney_id) REFERENCES tourney (id)');
        $this->addSql('ALTER TABLE confrontation ADD CONSTRAINT FK_EB1249E773B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE confrontation ADD CONSTRAINT FK_EB1249E721B741A9 FOREIGN KEY (ref_id) REFERENCES tourney_staff (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5ECAE3834 FOREIGN KEY (tourney_id) REFERENCES tourney (id)');
        $this->addSql('ALTER TABLE group_confrontation ADD CONSTRAINT FK_B4FB87A5FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_confrontation ADD CONSTRAINT FK_B4FB87A518EE86FA FOREIGN KEY (confrontation_id) REFERENCES confrontation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_player ADD CONSTRAINT FK_4B9FA2B599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE group_player ADD CONSTRAINT FK_4B9FA2B57B3406DF FOREIGN KEY (pool_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE lobbie ADD CONSTRAINT FK_860DEBE1ECAE3834 FOREIGN KEY (tourney_id) REFERENCES tourney (id)');
        $this->addSql('ALTER TABLE lobbie ADD CONSTRAINT FK_860DEBE173B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE mappool_map ADD CONSTRAINT FK_B50D204AC6833A60 FOREIGN KEY (mappool_id) REFERENCES mappool (id)');
        $this->addSql('ALTER TABLE mappool_map ADD CONSTRAINT FK_B50D204AC60DD20A FOREIGN KEY (beatmap_id) REFERENCES beatmap (id)');
        $this->addSql('ALTER TABLE mappool_map ADD CONSTRAINT FK_B50D204AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65ECAE3834 FOREIGN KEY (tourney_id) REFERENCES tourney (id)');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA3499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34F079958C FOREIGN KEY (mappool_map_id) REFERENCES mappool_map (id)');
        $this->addSql('ALTER TABLE team_user ADD CONSTRAINT FK_5C72223299E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE team_user ADD CONSTRAINT FK_5C722232296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE tourney_staff ADD CONSTRAINT FK_5EF8D741A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tourney_staff ADD CONSTRAINT FK_5EF8D741ECAE3834 FOREIGN KEY (tourney_id) REFERENCES tourney (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mappool_map DROP FOREIGN KEY FK_B50D204AC60DD20A');
        $this->addSql('ALTER TABLE ban DROP FOREIGN KEY FK_62FED0E518EE86FA');
        $this->addSql('ALTER TABLE group_confrontation DROP FOREIGN KEY FK_B4FB87A518EE86FA');
        $this->addSql('ALTER TABLE group_confrontation DROP FOREIGN KEY FK_B4FB87A5FE54D947');
        $this->addSql('ALTER TABLE group_player DROP FOREIGN KEY FK_4B9FA2B57B3406DF');
        $this->addSql('ALTER TABLE mappool_map DROP FOREIGN KEY FK_B50D204AC6833A60');
        $this->addSql('ALTER TABLE ban DROP FOREIGN KEY FK_62FED0E5F079958C');
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA34F079958C');
        $this->addSql('ALTER TABLE group_player DROP FOREIGN KEY FK_4B9FA2B599E6F5DF');
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA3499E6F5DF');
        $this->addSql('ALTER TABLE team_user DROP FOREIGN KEY FK_5C72223299E6F5DF');
        $this->addSql('ALTER TABLE confrontation DROP FOREIGN KEY FK_EB1249E773B21E9C');
        $this->addSql('ALTER TABLE lobbie DROP FOREIGN KEY FK_860DEBE173B21E9C');
        $this->addSql('ALTER TABLE team_user DROP FOREIGN KEY FK_5C722232296CD8AE');
        $this->addSql('ALTER TABLE confrontation DROP FOREIGN KEY FK_EB1249E7ECAE3834');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5ECAE3834');
        $this->addSql('ALTER TABLE lobbie DROP FOREIGN KEY FK_860DEBE1ECAE3834');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65ECAE3834');
        $this->addSql('ALTER TABLE tourney_staff DROP FOREIGN KEY FK_5EF8D741ECAE3834');
        $this->addSql('ALTER TABLE confrontation DROP FOREIGN KEY FK_EB1249E721B741A9');
        $this->addSql('ALTER TABLE mappool_map DROP FOREIGN KEY FK_B50D204AA76ED395');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65A76ED395');
        $this->addSql('ALTER TABLE tourney_staff DROP FOREIGN KEY FK_5EF8D741A76ED395');
        $this->addSql('DROP TABLE announce');
        $this->addSql('DROP TABLE ban');
        $this->addSql('DROP TABLE beatmap');
        $this->addSql('DROP TABLE beatmapset');
        $this->addSql('DROP TABLE blacklisted');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE confrontation');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_confrontation');
        $this->addSql('DROP TABLE group_player');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE lobbie');
        $this->addSql('DROP TABLE mappool');
        $this->addSql('DROP TABLE mappool_map');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE pool_set');
        $this->addSql('DROP TABLE round');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_user');
        $this->addSql('DROP TABLE tourney');
        $this->addSql('DROP TABLE tourney_staff');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE widget');
    }
}
