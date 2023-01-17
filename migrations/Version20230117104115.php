<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117104115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden ADD autore_braden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden ADD CONSTRAINT FK_150FFD348BA978 FOREIGN KEY (autore_braden_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_150FFD348BA978 ON SCHEDA_PAI_braden (autore_braden_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden DROP FOREIGN KEY FK_150FFD348BA978');
        $this->addSql('DROP INDEX IDX_150FFD348BA978 ON SCHEDA_PAI_braden');
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden DROP autore_braden_id');
    }
}
