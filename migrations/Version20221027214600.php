<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027214600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit_throw_config CHANGE launcher launcher INT DEFAULT NULL, CHANGE power power INT DEFAULT NULL, CHANGE angle angle INT DEFAULT NULL, CHANGE sound sound TINYINT(1) DEFAULT NULL, CHANGE light light TINYINT(1) DEFAULT NULL, CHANGE start_place start_place VARCHAR(255) DEFAULT NULL, CHANGE sort sort INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit_throw_config CHANGE launcher launcher INT NOT NULL, CHANGE power power INT NOT NULL, CHANGE angle angle INT NOT NULL, CHANGE sound sound TINYINT(1) NOT NULL, CHANGE light light TINYINT(1) NOT NULL, CHANGE start_place start_place VARCHAR(255) NOT NULL, CHANGE sort sort INT NOT NULL');
    }
}
