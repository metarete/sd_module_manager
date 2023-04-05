<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405134737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE valutazione_figura_professionale_obiettivi (valutazione_figura_professionale_id INT NOT NULL, obiettivi_id INT NOT NULL, INDEX IDX_BD39316692AA5E97 (valutazione_figura_professionale_id), INDEX IDX_BD393166A4D775BA (obiettivi_id), PRIMARY KEY(valutazione_figura_professionale_id, obiettivi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_obiettivi ADD CONSTRAINT FK_BD39316692AA5E97 FOREIGN KEY (valutazione_figura_professionale_id) REFERENCES SCHEDA_PAI_valutazione_figura_professionale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_obiettivi ADD CONSTRAINT FK_BD393166A4D775BA FOREIGN KEY (obiettivi_id) REFERENCES obiettivi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP FOREIGN KEY FK_B0168241A4D775BA');
        $this->addSql('DROP INDEX IDX_B0168241A4D775BA ON SCHEDA_PAI_valutazione_figura_professionale');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP obiettivi_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valutazione_figura_professionale_obiettivi DROP FOREIGN KEY FK_BD39316692AA5E97');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_obiettivi DROP FOREIGN KEY FK_BD393166A4D775BA');
        $this->addSql('DROP TABLE valutazione_figura_professionale_obiettivi');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD obiettivi_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD CONSTRAINT FK_B0168241A4D775BA FOREIGN KEY (obiettivi_id) REFERENCES obiettivi (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B0168241A4D775BA ON SCHEDA_PAI_valutazione_figura_professionale (obiettivi_id)');
    }
}
