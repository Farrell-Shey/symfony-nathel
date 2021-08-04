<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804095551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, osu_id INT NOT NULL, name VARCHAR(255) NOT NULL, token VARCHAR(1024) NOT NULL, discord VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, cover VARCHAR(255) DEFAULT NULL, timezone INT DEFAULT NULL, country VARCHAR(255) NOT NULL, silver_ss INT DEFAULT NULL, count_ss INT DEFAULT NULL, count_silver_s INT DEFAULT NULL, count_s INT DEFAULT NULL, count_a INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, game_mode_std INT DEFAULT NULL, game_mode_mania INT DEFAULT NULL, game_mode_taiko INT DEFAULT NULL, game_mode_ctb INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
