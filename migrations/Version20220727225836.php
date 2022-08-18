<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727225836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training_series (id INT AUTO_INCREMENT NOT NULL, training_unit_id INT NOT NULL, screens_count INT DEFAULT NULL, screens_configuration LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', target_type VARCHAR(50) DEFAULT NULL, target_configuration LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', player_tasks LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', series_volume INT DEFAULT NULL, sound INT DEFAULT NULL, sound_volume INT DEFAULT NULL, time_configuration LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', breaks_configuration LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', training_objectives LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', sort INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9FCC6422583E003 (training_unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_unit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, age_category VARCHAR(50) NOT NULL, training_type VARCHAR(50) NOT NULL, test VARCHAR(50) NOT NULL, training_group INT NOT NULL, training_sub_groups_age_categories LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', training_sub_groups_levels LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', series_count INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_unit_throw_config (id INT AUTO_INCREMENT NOT NULL, training_unit_id INT NOT NULL, launcher INT NOT NULL, power INT NOT NULL, angle INT NOT NULL, sound TINYINT(1) NOT NULL, light TINYINT(1) NOT NULL, start_place LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', sort INT NOT NULL, INDEX IDX_8D312C912583E003 (training_unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_series ADD CONSTRAINT FK_9FCC6422583E003 FOREIGN KEY (training_unit_id) REFERENCES training_unit (id)');
        $this->addSql('ALTER TABLE training_unit_throw_config ADD CONSTRAINT FK_8D312C912583E003 FOREIGN KEY (training_unit_id) REFERENCES training_unit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_series DROP FOREIGN KEY FK_9FCC6422583E003');
        $this->addSql('ALTER TABLE training_unit_throw_config DROP FOREIGN KEY FK_8D312C912583E003');
        $this->addSql('DROP TABLE training_series');
        $this->addSql('DROP TABLE training_unit');
        $this->addSql('DROP TABLE training_unit_throw_config');
    }
}
