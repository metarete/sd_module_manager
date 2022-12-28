<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228101212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE67FBDB8 FOREIGN KEY (id_valutazione_generale_id) REFERENCES SCHEDA_PAI_valutazione_generale (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE50254F30 FOREIGN KEY (id_parere_mmg_id) REFERENCES SCHEDA_PAI_parere_mmg (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE51D31DF FOREIGN KEY (id_chiusura_servizio_id) REFERENCES SCHEDA_PAI_chiusura_servizio (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD scheda_pai_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD CONSTRAINT FK_90EA910F32524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('CREATE INDEX IDX_90EA910F32524E3D ON SCHEDA_PAI_barthel (scheda_pai_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden CHANGE totale totale INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden ADD CONSTRAINT FK_150FFD332524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni ADD CONSTRAINT FK_5286357732524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_spmsq ADD CONSTRAINT FK_652E7A3F32524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_tinetti ADD CONSTRAINT FK_38D7D6432524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD CONSTRAINT FK_B016824132524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale CHANGE tipologia_valutazione tipologia_valutazione ENUM(\'Valutazione Iniziale\', \'Valutazione Ordinaria\', \'Valutazione Straordinaria\') NOT NULL COMMENT \'(DC2Type:Valutazione)\', CHANGE panf panf ENUM(\'presenza con funzione di care giver\', \'presenza senza funzione di care giver\', \'non presente\') NOT NULL COMMENT \'(DC2Type:PANF)\', CHANGE fanf fanf ENUM(\'presenza 24h su 24\', \'presenza saltuaria a ore nell arco della settimana\', \'solo giorni feriali\') NOT NULL COMMENT \'(DC2Type:FANF)\', CHANGE iss iss ENUM(\'presente\', \'presenza parziale o temporanea\', \'non presente\') NOT NULL COMMENT \'(DC2Type:ISS)\', CHANGE uso_servizi_igenici uso_servizi_igenici ENUM(\'autonomo\', \'parzialmente dipendente\', \'totalmente dipendente\') NOT NULL COMMENT \'(DC2Type:Autonomia)\', CHANGE abbigliamento abbigliamento ENUM(\'autonomo\', \'parzialmente dipendente\', \'totalmente dipendente\') NOT NULL COMMENT \'(DC2Type:Autonomia)\', CHANGE alimentazione alimentazione ENUM(\'autonomo\', \'parzialmente dipendente\', \'totalmente dipendente\') NOT NULL COMMENT \'(DC2Type:Autonomia)\', CHANGE indicatore_deambulazione indicatore_deambulazione ENUM(\'autonomo\', \'parzialmente dipendente\', \'totalmente dipendente\') NOT NULL COMMENT \'(DC2Type:Autonomia)\', CHANGE igene_personale igene_personale ENUM(\'autonomo\', \'parzialmente dipendente\', \'totalmente dipendente\') NOT NULL COMMENT \'(DC2Type:Autonomia)\', CHANGE cognitivita cognitivita ENUM(\'assenti/lievi\', \'moderati\', \'gravi\') NOT NULL COMMENT \'(DC2Type:Disturbi)\', CHANGE comportamento comportamento ENUM(\'assenti/lievi\', \'moderati\', \'gravi\') NOT NULL COMMENT \'(DC2Type:Disturbi)\'');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas ADD CONSTRAINT FK_D92348332524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
        $this->addSql('ALTER TABLE paziente ADD id_sd_manager INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE67FBDB8');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE50254F30');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE51D31DF');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP FOREIGN KEY FK_90EA910F32524E3D');
        $this->addSql('DROP INDEX IDX_90EA910F32524E3D ON SCHEDA_PAI_barthel');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP scheda_pai_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden DROP FOREIGN KEY FK_150FFD332524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_braden CHANGE totale totale INT NOT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni DROP FOREIGN KEY FK_5286357732524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_spmsq DROP FOREIGN KEY FK_652E7A3F32524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_tinetti DROP FOREIGN KEY FK_38D7D6432524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP FOREIGN KEY FK_B016824132524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale CHANGE tipologia_valutazione tipologia_valutazione VARCHAR(255) NOT NULL, CHANGE panf panf VARCHAR(255) NOT NULL, CHANGE fanf fanf VARCHAR(255) NOT NULL, CHANGE iss iss VARCHAR(255) NOT NULL, CHANGE uso_servizi_igenici uso_servizi_igenici VARCHAR(255) NOT NULL, CHANGE abbigliamento abbigliamento VARCHAR(255) NOT NULL, CHANGE alimentazione alimentazione VARCHAR(255) NOT NULL, CHANGE indicatore_deambulazione indicatore_deambulazione VARCHAR(255) NOT NULL, CHANGE igene_personale igene_personale VARCHAR(255) NOT NULL, CHANGE cognitivita cognitivita VARCHAR(255) NOT NULL, CHANGE comportamento comportamento VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas DROP FOREIGN KEY FK_D92348332524E3D');
        $this->addSql('ALTER TABLE paziente DROP id_sd_manager');
    }
}
