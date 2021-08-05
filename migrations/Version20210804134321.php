<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804134321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announce (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beatmap (id INT AUTO_INCREMENT NOT NULL, difficulty VARCHAR(255) NOT NULL, bpm INT NOT NULL, ar INT NOT NULL, cs INT NOT NULL, drain INT NOT NULL, accuracy INT NOT NULL, hit_length INT NOT NULL, mode_int VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beatmapset (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, creator VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blacklisted (id INT AUTO_INCREMENT NOT NULL, reason VARCHAR(255) NOT NULL, is_admin_approved TINYINT(1) DEFAULT NULL, is_ban_all_over TINYINT(1) DEFAULT NULL, severity VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, addlike INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, size INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, is_accept TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lobbie (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, is_replay TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mappool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, follow INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pool_set (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, step VARCHAR(255) NOT NULL, date DATETIME NOT NULL, position INT NOT NULL, best_of INT DEFAULT NULL, bans INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tourney (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, iteration INT NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, background_home VARCHAR(255) DEFAULT NULL, follow INT NOT NULL, nb_players INT NOT NULL, nb_inscrits VARCHAR(255) NOT NULL, discord VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, forum_post VARCHAR(255) DEFAULT NULL, mode VARCHAR(255) NOT NULL, is_scorev2 TINYINT(1) NOT NULL, is_scale VARCHAR(255) DEFAULT NULL, is_team TINYINT(1) NOT NULL, is_qualif TINYINT(1) NOT NULL, groupstages TINYINT(1) NOT NULL, bracket_format VARCHAR(255) NOT NULL, max_pt INT DEFAULT NULL, max_reg INT DEFAULT NULL, round_of INT DEFAULT NULL, reg_start_date DATETIME NOT NULL, reg_close_date DATETIME NOT NULL, color_theme VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, osu_id INT NOT NULL, name VARCHAR(255) NOT NULL, token VARCHAR(1024) NOT NULL, discord VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, cover VARCHAR(255) DEFAULT NULL, timezone INT DEFAULT NULL, country VARCHAR(255) NOT NULL, silver_ss INT DEFAULT NULL, count_ss INT DEFAULT NULL, count_silver_s INT DEFAULT NULL, count_s INT DEFAULT NULL, count_a INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, game_mode_std INT DEFAULT NULL, game_mode_mania INT DEFAULT NULL, game_mode_taiko INT DEFAULT NULL, game_mode_ctb INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, page VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE announce');
        $this->addSql('DROP TABLE beatmap');
        $this->addSql('DROP TABLE beatmapset');
        $this->addSql('DROP TABLE blacklisted');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE lobbie');
        $this->addSql('DROP TABLE mappool');
        $this->addSql('DROP TABLE pool_set');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tourney');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE widget');
    }
}
