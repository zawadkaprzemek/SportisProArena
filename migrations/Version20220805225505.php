<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220805225505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sound (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_series CHANGE sound surrounding_sound_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_series ADD CONSTRAINT FK_9FCC64278B31D29 FOREIGN KEY (surrounding_sound_id) REFERENCES sound (id)');
        $this->addSql('CREATE INDEX IDX_9FCC64278B31D29 ON training_series (surrounding_sound_id)');
        $this->addSql('INSERT INTO sound (name,path) values ("Gwizdy","gwizdy.mp4"),("Bębny","bebny.mp4"),("Krzyki","krzyki.mp4"),("Aplauz","aplauz.mp4")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_series DROP FOREIGN KEY FK_9FCC64278B31D29');
        $this->addSql('DROP TABLE sound');
        $this->addSql('DROP INDEX IDX_9FCC64278B31D29 ON training_series');
        $this->addSql('ALTER TABLE training_series CHANGE surrounding_sound_id sound INT DEFAULT NULL');
    }
}
