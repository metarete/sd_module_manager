<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206124802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE67FBDB8');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE67FBDB8 FOREIGN KEY (id_valutazione_generale_id) REFERENCES SCHEDA_PAI_valutazione_generale (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE67FBDB8');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE67FBDB8 FOREIGN KEY (id_valutazione_generale_id) REFERENCES SCHEDA_PAI_valutazione_generale (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
