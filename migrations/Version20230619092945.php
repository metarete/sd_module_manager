<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619092945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipi_adiweb (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, descrizione VARCHAR(255) NOT NULL, codice INT NOT NULL, adiweb_id_prestazione INT NOT NULL, tipo_operatore VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipi_adiweb_valutazione_figura_professionale (tipi_adiweb_id INT NOT NULL, valutazione_figura_professionale_id INT NOT NULL, INDEX IDX_F1CDD9FAA29FB5DD (tipi_adiweb_id), INDEX IDX_F1CDD9FA92AA5E97 (valutazione_figura_professionale_id), PRIMARY KEY(tipi_adiweb_id, valutazione_figura_professionale_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale ADD CONSTRAINT FK_F1CDD9FAA29FB5DD FOREIGN KEY (tipi_adiweb_id) REFERENCES tipi_adiweb (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale ADD CONSTRAINT FK_F1CDD9FA92AA5E97 FOREIGN KEY (valutazione_figura_professionale_id) REFERENCES SCHEDA_PAI_valutazione_figura_professionale (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale DROP FOREIGN KEY FK_F1CDD9FAA29FB5DD');
        $this->addSql('ALTER TABLE tipi_adiweb_valutazione_figura_professionale DROP FOREIGN KEY FK_F1CDD9FA92AA5E97');
        $this->addSql('DROP TABLE tipi_adiweb');
        $this->addSql('DROP TABLE tipi_adiweb_valutazione_figura_professionale');
    }
}
