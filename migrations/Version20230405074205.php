<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405074205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE SCHEDA_PAI_painad (id INT AUTO_INCREMENT NOT NULL, scheda_pai_id INT DEFAULT NULL, autore_painad_id INT DEFAULT NULL, data_valutazione DATE NOT NULL, INDEX IDX_2494303232524E3D (scheda_pai_id), INDEX IDX_249430327CC0DEE2 (autore_painad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE SCHEDA_PAI_painad ADD CONSTRAINT FK_2494303232524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SCHEDA_PAI_painad ADD CONSTRAINT FK_249430327CC0DEE2 FOREIGN KEY (autore_painad_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD abilita_painad TINYINT(1) NOT NULL, ADD numero_painad_corretto INT NOT NULL, ADD frequenza_painad INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_painad DROP FOREIGN KEY FK_2494303232524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_painad DROP FOREIGN KEY FK_249430327CC0DEE2');
        $this->addSql('DROP TABLE SCHEDA_PAI_painad');
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP abilita_painad, DROP numero_painad_corretto, DROP frequenza_painad');
    }
}
