<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619103230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE valutazione_figura_professionale_tipi_adiweb (valutazione_figura_professionale_id INT NOT NULL, tipi_adiweb_id INT NOT NULL, INDEX IDX_C29E561A92AA5E97 (valutazione_figura_professionale_id), INDEX IDX_C29E561AA29FB5DD (tipi_adiweb_id), PRIMARY KEY(valutazione_figura_professionale_id, tipi_adiweb_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_tipi_adiweb ADD CONSTRAINT FK_C29E561A92AA5E97 FOREIGN KEY (valutazione_figura_professionale_id) REFERENCES SCHEDA_PAI_valutazione_figura_professionale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_tipi_adiweb ADD CONSTRAINT FK_C29E561AA29FB5DD FOREIGN KEY (tipi_adiweb_id) REFERENCES tipi_adiweb (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale DROP FOREIGN KEY FK_F1CDD9FA92AA5E97');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale DROP FOREIGN KEY FK_F1CDD9FAA29FB5DD');
        $this->addSql('DROP TABLE tipi_adiweb_valutazione_figura_professionale');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipi_adiweb_valutazione_figura_professionale (tipi_adiweb_id INT NOT NULL, valutazione_figura_professionale_id INT NOT NULL, INDEX IDX_F1CDD9FAA29FB5DD (tipi_adiweb_id), INDEX IDX_F1CDD9FA92AA5E97 (valutazione_figura_professionale_id), PRIMARY KEY(tipi_adiweb_id, valutazione_figura_professionale_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale ADD CONSTRAINT FK_F1CDD9FA92AA5E97 FOREIGN KEY (valutazione_figura_professionale_id) REFERENCES SCHEDA_PAI_valutazione_figura_professionale (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale ADD CONSTRAINT FK_F1CDD9FAA29FB5DD FOREIGN KEY (tipi_adiweb_id) REFERENCES tipi_adiweb (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_tipi_adiweb DROP FOREIGN KEY FK_C29E561A92AA5E97');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_tipi_adiweb DROP FOREIGN KEY FK_C29E561AA29FB5DD');
        $this->addSql('DROP TABLE valutazione_figura_professionale_tipi_adiweb');
    }
}
