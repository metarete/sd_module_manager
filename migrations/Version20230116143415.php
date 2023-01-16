<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116143415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE51D31DF');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE50254F30');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE51D31DF FOREIGN KEY (id_chiusura_servizio_id) REFERENCES SCHEDA_PAI_chiusura_servizio (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE50254F30 FOREIGN KEY (id_parere_mmg_id) REFERENCES SCHEDA_PAI_parere_mmg (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE50254F30');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP FOREIGN KEY FK_521766CE51D31DF');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE50254F30 FOREIGN KEY (id_parere_mmg_id) REFERENCES SCHEDA_PAI_parere_mmg (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD CONSTRAINT FK_521766CE51D31DF FOREIGN KEY (id_chiusura_servizio_id) REFERENCES SCHEDA_PAI_chiusura_servizio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
