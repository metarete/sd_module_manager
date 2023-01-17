<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117114534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni ADD autore_lesioni_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni ADD CONSTRAINT FK_52863577AEB51382 FOREIGN KEY (autore_lesioni_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_52863577AEB51382 ON SCHEDA_PAI_lesioni (autore_lesioni_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_parere_mmg ADD autore_parere_mmg_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_parere_mmg ADD CONSTRAINT FK_3D3327FDCDCC9161 FOREIGN KEY (autore_parere_mmg_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D3327FDCDCC9161 ON SCHEDA_PAI_parere_mmg (autore_parere_mmg_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_spmsq ADD autore_spmsq_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_spmsq ADD CONSTRAINT FK_652E7A3FD0161416 FOREIGN KEY (autore_spmsq_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_652E7A3FD0161416 ON SCHEDA_PAI_spmsq (autore_spmsq_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_tinetti ADD autore_tinetti_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_tinetti ADD CONSTRAINT FK_38D7D641FA7127F FOREIGN KEY (autore_tinetti_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_38D7D641FA7127F ON SCHEDA_PAI_tinetti (autore_tinetti_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD autore_valutazione_professionale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD CONSTRAINT FK_B0168241E96403 FOREIGN KEY (autore_valutazione_professionale_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B0168241E96403 ON SCHEDA_PAI_valutazione_figura_professionale (autore_valutazione_professionale_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale ADD autore_valutazione_generale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale ADD CONSTRAINT FK_C75DEFA69EECE3D3 FOREIGN KEY (autore_valutazione_generale_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C75DEFA69EECE3D3 ON SCHEDA_PAI_valutazione_generale (autore_valutazione_generale_id)');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas ADD autore_vas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas ADD CONSTRAINT FK_D92348348FB292 FOREIGN KEY (autore_vas_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D92348348FB292 ON SCHEDA_PAI_vas (autore_vas_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni DROP FOREIGN KEY FK_52863577AEB51382');
        $this->addSql('DROP INDEX IDX_52863577AEB51382 ON SCHEDA_PAI_lesioni');
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni DROP autore_lesioni_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_parere_mmg DROP FOREIGN KEY FK_3D3327FDCDCC9161');
        $this->addSql('DROP INDEX IDX_3D3327FDCDCC9161 ON SCHEDA_PAI_parere_mmg');
        $this->addSql('ALTER TABLE SCHEDA_PAI_parere_mmg DROP autore_parere_mmg_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_spmsq DROP FOREIGN KEY FK_652E7A3FD0161416');
        $this->addSql('DROP INDEX IDX_652E7A3FD0161416 ON SCHEDA_PAI_spmsq');
        $this->addSql('ALTER TABLE SCHEDA_PAI_spmsq DROP autore_spmsq_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_tinetti DROP FOREIGN KEY FK_38D7D641FA7127F');
        $this->addSql('DROP INDEX IDX_38D7D641FA7127F ON SCHEDA_PAI_tinetti');
        $this->addSql('ALTER TABLE SCHEDA_PAI_tinetti DROP autore_tinetti_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP FOREIGN KEY FK_B0168241E96403');
        $this->addSql('DROP INDEX IDX_B0168241E96403 ON SCHEDA_PAI_valutazione_figura_professionale');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP autore_valutazione_professionale_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale DROP FOREIGN KEY FK_C75DEFA69EECE3D3');
        $this->addSql('DROP INDEX IDX_C75DEFA69EECE3D3 ON SCHEDA_PAI_valutazione_generale');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale DROP autore_valutazione_generale_id');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas DROP FOREIGN KEY FK_D92348348FB292');
        $this->addSql('DROP INDEX IDX_D92348348FB292 ON SCHEDA_PAI_vas');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas DROP autore_vas_id');
    }
}
