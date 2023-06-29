<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629082115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pratica (id INT AUTO_INCREMENT NOT NULL, codice VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD adiweb_pratica_id INT DEFAULT NULL, DROP adiweb_pratica');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE9048B05C FOREIGN KEY (adiweb_pratica_id) REFERENCES pratica (id)');
        $this->addSql('CREATE INDEX IDX_521766CE9048B05C ON SCHEDA_PAI (adiweb_pratica_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE9048B05C');
        $this->addSql('DROP TABLE pratica');
        $this->addSql('DROP INDEX IDX_521766CE9048B05C ON SCHEDA_PAI');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD adiweb_pratica VARCHAR(255) DEFAULT NULL, DROP adiweb_pratica_id');
    }
}
