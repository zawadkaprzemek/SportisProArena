<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727215103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit DROP FOREIGN KEY FK_D3214B1073F32DD8');
        $this->addSql('ALTER TABLE training_unit_training_objectives DROP FOREIGN KEY FK_BC5E44EF267BFBEB');
        $this->addSql('ALTER TABLE training_unit_training_objectives DROP FOREIGN KEY FK_BC5E44EF2583E003');
        $this->addSql('DROP TABLE training_configuration');
        $this->addSql('DROP TABLE training_objectives');
        $this->addSql('DROP TABLE training_unit');
        $this->addSql('DROP TABLE training_unit_training_objectives');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training_configuration (id INT AUTO_INCREMENT NOT NULL, trainer_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, status INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DB4BCA46FB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE training_objectives (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE training_unit (id INT AUTO_INCREMENT NOT NULL, configuration_id INT NOT NULL, age_category VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, training_type VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, training_group INT NOT NULL, training_sub_groups LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', series_count INT NOT NULL, screens_count INT NOT NULL, screens_configuration LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', target_type INT NOT NULL, target_configuration LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', player_tasks LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', throws_count INT NOT NULL, throws_configuration LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', sound INT NOT NULL, sound_volume INT NOT NULL, time_configuration LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', breaks_configuration LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', status INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, sort INT NOT NULL, test VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D3214B1073F32DD8 (configuration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE training_unit_training_objectives (training_unit_id INT NOT NULL, training_objectives_id INT NOT NULL, INDEX IDX_BC5E44EF2583E003 (training_unit_id), INDEX IDX_BC5E44EF267BFBEB (training_objectives_id), PRIMARY KEY(training_unit_id, training_objectives_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE training_configuration ADD CONSTRAINT FK_DB4BCA46FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE training_unit ADD CONSTRAINT FK_D3214B1073F32DD8 FOREIGN KEY (configuration_id) REFERENCES training_configuration (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE training_unit_training_objectives ADD CONSTRAINT FK_BC5E44EF2583E003 FOREIGN KEY (training_unit_id) REFERENCES training_unit (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE training_unit_training_objectives ADD CONSTRAINT FK_BC5E44EF267BFBEB FOREIGN KEY (training_objectives_id) REFERENCES training_objectives (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
