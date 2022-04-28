<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427152437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_manager (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, manager_id INT NOT NULL, accepted TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_9BD0DAFC99E6F5DF (player_id), INDEX IDX_9BD0DAFC783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_manager ADD CONSTRAINT FK_9BD0DAFC99E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE player_manager ADD CONSTRAINT FK_9BD0DAFC783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE player_manager');
    }
}
