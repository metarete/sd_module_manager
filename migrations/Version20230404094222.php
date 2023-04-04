<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404094222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni CHANGE numero_sede_lesione numero_sede_lesione INT NOT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_parere_mmg CHANGE descrizione descrizione LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni CHANGE numero_sede_lesione numero_sede_lesione VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_parere_mmg CHANGE descrizione descrizione LONGTEXT DEFAULT NULL');
    }
}
