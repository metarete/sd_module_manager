<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019144620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chiusura_forzata (id INT AUTO_INCREMENT NOT NULL, autore_chiusura_forzata_id INT DEFAULT NULL, data DATE NOT NULL, conclusioni LONGTEXT NOT NULL, motivazione_chiusura_forzata ENUM(\'Completamento del programma assistenziale\', \'Decesso a domicilio\', \'Decesso in Hospice\', \'Decesso in ospedale\', \'Ricovero in ospedale\', \'Trasferimento in altro tipo di cure domiciliari\', \'Trasferimento in Hospice\', \'Trasferimento in residenza sanitaria\', \'Volontà dell utente o familiare\', \'Non necessita di ulteriori valutazioni\', \'Altro\') NOT NULL COMMENT \'(DC2Type:MotivazioneChiusuraForzata)\', INDEX IDX_B371B30B6BBF3503 (autore_chiusura_forzata_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chiusura_forzata ADD CONSTRAINT FK_B371B30B6BBF3503 FOREIGN KEY (autore_chiusura_forzata_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD id_chiusura_forzata_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CEC4140A78 FOREIGN KEY (id_chiusura_forzata_id) REFERENCES chiusura_forzata (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_521766CEC4140A78 ON SCHEDA_PAI (id_chiusura_forzata_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CEC4140A78');
        $this->addSql('ALTER TABLE chiusura_forzata DROP FOREIGN KEY FK_B371B30B6BBF3503');
        $this->addSql('DROP TABLE chiusura_forzata');
        $this->addSql('DROP INDEX UNIQ_521766CEC4140A78 ON SCHEDA_PAI');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP id_chiusura_forzata_id');
    }
}
