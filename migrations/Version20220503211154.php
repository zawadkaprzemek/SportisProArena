<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503211154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arena (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_session (id INT AUTO_INCREMENT NOT NULL, buyer_id INT NOT NULL, arena_id INT NOT NULL, status INT NOT NULL, session_date DATETIME NOT NULL, INDEX IDX_D7A45DA6C755722 (buyer_id), INDEX IDX_D7A45DA663565CF (arena_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_session ADD CONSTRAINT FK_D7A45DA6C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE training_session ADD CONSTRAINT FK_D7A45DA663565CF FOREIGN KEY (arena_id) REFERENCES arena (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_session DROP FOREIGN KEY FK_D7A45DA663565CF');
        $this->addSql('DROP TABLE arena');
        $this->addSql('DROP TABLE training_session');
    }
}
