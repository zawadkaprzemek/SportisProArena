<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727230043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit ADD trainer_id INT NOT NULL');
        $this->addSql('ALTER TABLE training_unit ADD CONSTRAINT FK_D3214B10FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D3214B10FB08EDF6 ON training_unit (trainer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit DROP FOREIGN KEY FK_D3214B10FB08EDF6');
        $this->addSql('DROP INDEX IDX_D3214B10FB08EDF6 ON training_unit');
        $this->addSql('ALTER TABLE training_unit DROP trainer_id');
    }
}
