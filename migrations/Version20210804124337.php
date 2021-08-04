<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804124337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announce (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blaclisted (id INT AUTO_INCREMENT NOT NULL, reason VARCHAR(255) NOT NULL, is_admin_approved TINYINT(1) DEFAULT NULL, is_ban_all_over TINYINT(1) DEFAULT NULL, severity VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, addlike INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, step VARCHAR(255) NOT NULL, date DATETIME NOT NULL, position INT NOT NULL, best_of INT DEFAULT NULL, bans INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tourneys (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, iteration INT NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, background_home VARCHAR(255) DEFAULT NULL, follow INT NOT NULL, nb_players INT NOT NULL, nb_inscrits VARCHAR(255) NOT NULL, discord VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, forum_post VARCHAR(255) DEFAULT NULL, mode VARCHAR(255) NOT NULL, is_scorev2 TINYINT(1) NOT NULL, is_scale VARCHAR(255) DEFAULT NULL, is_team TINYINT(1) NOT NULL, is_qualif TINYINT(1) NOT NULL, groupstages TINYINT(1) NOT NULL, bracket_format VARCHAR(255) NOT NULL, max_pt INT DEFAULT NULL, max_reg INT DEFAULT NULL, round_of INT DEFAULT NULL, reg_start_date DATETIME NOT NULL, reg_close_date DATETIME NOT NULL, color_theme VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, page VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE announce');
        $this->addSql('DROP TABLE blaclisted');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE tourneys');
        $this->addSql('DROP TABLE widget');
    }
}
