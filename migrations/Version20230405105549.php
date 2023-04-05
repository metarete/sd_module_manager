<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405105549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE obiettivi (id INT AUTO_INCREMENT NOT NULL, titolo VARCHAR(255) NOT NULL, descrizione LONGTEXT NOT NULL, stato TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD obiettivi_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD CONSTRAINT FK_B0168241A4D775BA FOREIGN KEY (obiettivi_id) REFERENCES obiettivi (id)');
        $this->addSql('CREATE INDEX IDX_B0168241A4D775BA ON SCHEDA_PAI_valutazione_figura_professionale (obiettivi_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP FOREIGN KEY FK_B0168241A4D775BA');
        $this->addSql('DROP TABLE obiettivi');
        $this->addSql('DROP INDEX IDX_B0168241A4D775BA ON SCHEDA_PAI_valutazione_figura_professionale');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP obiettivi_id');
    }
}
