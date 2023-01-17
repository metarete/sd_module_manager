<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117105149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_chiusura_servizio ADD autore_chiusura_servizio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_chiusura_servizio ADD CONSTRAINT FK_E2387854C2653254 FOREIGN KEY (autore_chiusura_servizio_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E2387854C2653254 ON SCHEDA_PAI_chiusura_servizio (autore_chiusura_servizio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_chiusura_servizio DROP FOREIGN KEY FK_E2387854C2653254');
        $this->addSql('DROP INDEX IDX_E2387854C2653254 ON SCHEDA_PAI_chiusura_servizio');
        $this->addSql('ALTER TABLE SCHEDA_PAI_chiusura_servizio DROP autore_chiusura_servizio_id');
    }
}
