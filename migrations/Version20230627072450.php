<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627072450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD assistito_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CEA1485A3F FOREIGN KEY (assistito_id) REFERENCES paziente (id)');
        $this->addSql('CREATE INDEX IDX_521766CEA1485A3F ON SCHEDA_PAI (assistito_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CEA1485A3F');
        $this->addSql('DROP INDEX IDX_521766CEA1485A3F ON SCHEDA_PAI');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP assistito_id');
    }

    public function postUp(Schema $schema): void
    {

        $this->connection->executeQuery('UPDATE SCHEDA_PAI,paziente SET assistito_id = paziente.id WHERE SCHEDA_PAI.id_assistito = paziente.id_sd_manager');
    }
}
