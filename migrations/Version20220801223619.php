<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801223619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit_throw_config DROP FOREIGN KEY FK_8D312C912583E003');
        $this->addSql('DROP INDEX IDX_8D312C912583E003 ON training_unit_throw_config');
        $this->addSql('ALTER TABLE training_unit_throw_config CHANGE training_unit_id training_series_id INT NOT NULL');
        $this->addSql('ALTER TABLE training_unit_throw_config ADD CONSTRAINT FK_8D312C913CF45268 FOREIGN KEY (training_series_id) REFERENCES training_series (id)');
        $this->addSql('CREATE INDEX IDX_8D312C913CF45268 ON training_unit_throw_config (training_series_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_unit_throw_config DROP FOREIGN KEY FK_8D312C913CF45268');
        $this->addSql('DROP INDEX IDX_8D312C913CF45268 ON training_unit_throw_config');
        $this->addSql('ALTER TABLE training_unit_throw_config CHANGE training_series_id training_unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE training_unit_throw_config ADD CONSTRAINT FK_8D312C912583E003 FOREIGN KEY (training_unit_id) REFERENCES training_unit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D312C912583E003 ON training_unit_throw_config (training_unit_id)');
    }
}
