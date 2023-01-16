<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116095309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valutazione_generale_altra_tipologia_assistenza DROP FOREIGN KEY FK_54A250346CC3158');
        $this->addSql('ALTER TABLE valutazione_generale_altra_tipologia_assistenza DROP FOREIGN KEY FK_54A2503468146AE8');
        $this->addSql('ALTER TABLE valutazione_generale_bisogni DROP FOREIGN KEY FK_DD30FE7975A473DE');
        $this->addSql('ALTER TABLE valutazione_generale_bisogni DROP FOREIGN KEY FK_DD30FE7968146AE8');
        $this->addSql('DROP TABLE SCHEDA_PAI_altra_tipologia_assistenza');
        $this->addSql('DROP TABLE SCHEDA_PAI_bisogni');
        $this->addSql('DROP TABLE valutazione_generale_altra_tipologia_assistenza');
        $this->addSql('DROP TABLE valutazione_generale_bisogni');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale ADD buono_sociale TINYINT(1) NOT NULL, ADD trasporti TINYINT(1) NOT NULL, ADD voucher_sociale TINYINT(1) NOT NULL, ADD sad TINYINT(1) NOT NULL, ADD pasti TINYINT(1) NOT NULL, ADD assistenza_domiciliare TINYINT(1) NOT NULL, ADD contributo_caregiver TINYINT(1) NOT NULL, ADD broncoaspirazione TINYINT(1) NOT NULL, ADD ventilo_terapia TINYINT(1) NOT NULL, ADD alimentazione_assistita TINYINT(1) NOT NULL, ADD alimentazione_parenterale TINYINT(1) NOT NULL, ADD eliminazione_urina TINYINT(1) NOT NULL, ADD educazione_terapeutica TINYINT(1) NOT NULL, ADD ulcere_terzo_quarto_grado TINYINT(1) NOT NULL, ADD ulcere_cutanee_terzo_quarto_grado TINYINT(1) NOT NULL, ADD prelievi_venosi_occasionali TINYINT(1) NOT NULL, ADD telemetria TINYINT(1) NOT NULL, ADD gestione_catetere TINYINT(1) NOT NULL, ADD controllo_dolore TINYINT(1) NOT NULL, ADD assistenza_non_oncologica TINYINT(1) NOT NULL, ADD trattamento_ortopedico TINYINT(1) NOT NULL, ADD supervisione_continua TINYINT(1) NOT NULL, ADD assistenza_adl TINYINT(1) NOT NULL, ADD ossigeno_terapia TINYINT(1) NOT NULL, ADD tracheotomia TINYINT(1) NOT NULL, ADD alimentazione_enterale TINYINT(1) NOT NULL, ADD gestione_stomia TINYINT(1) NOT NULL, ADD alterazione_sonno TINYINT(1) NOT NULL, ADD ulcere_primo_secondo_grado TINYINT(1) NOT NULL, ADD ulcere_cutanee_primo_secondo_grado TINYINT(1) NOT NULL, ADD prelievi_venosi_non_occasionali TINYINT(1) NOT NULL, ADD ecg TINYINT(1) NOT NULL, ADD procedura_terapeutica TINYINT(1) NOT NULL, ADD trasfusioni TINYINT(1) NOT NULL, ADD assistenza_oncologica TINYINT(1) NOT NULL, ADD trattamento_neurologico TINYINT(1) NOT NULL, ADD trattamento_mantenimento TINYINT(1) NOT NULL, ADD assistenza_iadl TINYINT(1) NOT NULL, ADD supporto_caregiver TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE SCHEDA_PAI_altra_tipologia_assistenza (id INT AUTO_INCREMENT NOT NULL, nome LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE SCHEDA_PAI_bisogni (id INT AUTO_INCREMENT NOT NULL, nome LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE valutazione_generale_altra_tipologia_assistenza (valutazione_generale_id INT NOT NULL, altra_tipologia_assistenza_id INT NOT NULL, INDEX IDX_54A2503468146AE8 (valutazione_generale_id), INDEX IDX_54A250346CC3158 (altra_tipologia_assistenza_id), PRIMARY KEY(valutazione_generale_id, altra_tipologia_assistenza_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE valutazione_generale_bisogni (valutazione_generale_id INT NOT NULL, bisogni_id INT NOT NULL, INDEX IDX_DD30FE7968146AE8 (valutazione_generale_id), INDEX IDX_DD30FE7975A473DE (bisogni_id), PRIMARY KEY(valutazione_generale_id, bisogni_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE valutazione_generale_altra_tipologia_assistenza ADD CONSTRAINT FK_54A250346CC3158 FOREIGN KEY (altra_tipologia_assistenza_id) REFERENCES SCHEDA_PAI_altra_tipologia_assistenza (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_generale_altra_tipologia_assistenza ADD CONSTRAINT FK_54A2503468146AE8 FOREIGN KEY (valutazione_generale_id) REFERENCES SCHEDA_PAI_valutazione_generale (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_generale_bisogni ADD CONSTRAINT FK_DD30FE7975A473DE FOREIGN KEY (bisogni_id) REFERENCES SCHEDA_PAI_bisogni (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_generale_bisogni ADD CONSTRAINT FK_DD30FE7968146AE8 FOREIGN KEY (valutazione_generale_id) REFERENCES SCHEDA_PAI_valutazione_generale (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale DROP buono_sociale, DROP trasporti, DROP voucher_sociale, DROP sad, DROP pasti, DROP assistenza_domiciliare, DROP contributo_caregiver, DROP broncoaspirazione, DROP ventilo_terapia, DROP alimentazione_assistita, DROP alimentazione_parenterale, DROP eliminazione_urina, DROP educazione_terapeutica, DROP ulcere_terzo_quarto_grado, DROP ulcere_cutanee_terzo_quarto_grado, DROP prelievi_venosi_occasionali, DROP telemetria, DROP gestione_catetere, DROP controllo_dolore, DROP assistenza_non_oncologica, DROP trattamento_ortopedico, DROP supervisione_continua, DROP assistenza_adl, DROP ossigeno_terapia, DROP tracheotomia, DROP alimentazione_enterale, DROP gestione_stomia, DROP alterazione_sonno, DROP ulcere_primo_secondo_grado, DROP ulcere_cutanee_primo_secondo_grado, DROP prelievi_venosi_non_occasionali, DROP ecg, DROP procedura_terapeutica, DROP trasfusioni, DROP assistenza_oncologica, DROP trattamento_neurologico, DROP trattamento_mantenimento, DROP assistenza_iadl, DROP supporto_caregiver');
    }
}
