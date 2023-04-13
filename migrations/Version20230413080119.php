<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413080119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE valutazione_figura_professionale_diagnosi (valutazione_figura_professionale_id INT NOT NULL, diagnosi_id INT NOT NULL, INDEX IDX_50A59F5A92AA5E97 (valutazione_figura_professionale_id), INDEX IDX_50A59F5A7439F32A (diagnosi_id), PRIMARY KEY(valutazione_figura_professionale_id, diagnosi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diagnosi (id INT AUTO_INCREMENT NOT NULL, codice VARCHAR(255) NOT NULL, descrizione LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_diagnosi ADD CONSTRAINT FK_50A59F5A92AA5E97 FOREIGN KEY (valutazione_figura_professionale_id) REFERENCES SCHEDA_PAI_valutazione_figura_professionale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_diagnosi ADD CONSTRAINT FK_50A59F5A7439F32A FOREIGN KEY (diagnosi_id) REFERENCES diagnosi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale DROP diagnosi_professionale');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valutazione_figura_professionale_diagnosi DROP FOREIGN KEY FK_50A59F5A92AA5E97');
        $this->addSql('ALTER TABLE valutazione_figura_professionale_diagnosi DROP FOREIGN KEY FK_50A59F5A7439F32A');
        $this->addSql('DROP TABLE valutazione_figura_professionale_diagnosi');
        $this->addSql('DROP TABLE diagnosi');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_figura_professionale ADD diagnosi_professionale LONGTEXT NOT NULL');
    }
}
