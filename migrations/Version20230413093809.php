<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413093809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE valutazione_generale_diagnosi (valutazione_generale_id INT NOT NULL, diagnosi_id INT NOT NULL, INDEX IDX_BB8EF1A768146AE8 (valutazione_generale_id), INDEX IDX_BB8EF1A77439F32A (diagnosi_id), PRIMARY KEY(valutazione_generale_id, diagnosi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE valutazione_generale_diagnosi ADD CONSTRAINT FK_BB8EF1A768146AE8 FOREIGN KEY (valutazione_generale_id) REFERENCES SCHEDA_PAI_valutazione_generale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valutazione_generale_diagnosi ADD CONSTRAINT FK_BB8EF1A77439F32A FOREIGN KEY (diagnosi_id) REFERENCES diagnosi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale DROP diagnosi');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valutazione_generale_diagnosi DROP FOREIGN KEY FK_BB8EF1A768146AE8');
        $this->addSql('ALTER TABLE valutazione_generale_diagnosi DROP FOREIGN KEY FK_BB8EF1A77439F32A');
        $this->addSql('DROP TABLE valutazione_generale_diagnosi');
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale ADD diagnosi VARCHAR(255) NOT NULL');
    }
}
