<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505125939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_session ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_session ADD CONSTRAINT FK_D7A45DA99E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D7A45DA99E6F5DF ON training_session (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_session DROP FOREIGN KEY FK_D7A45DA99E6F5DF');
        $this->addSql('DROP INDEX IDX_D7A45DA99E6F5DF ON training_session');
        $this->addSql('ALTER TABLE training_session DROP player_id');
    }
}
