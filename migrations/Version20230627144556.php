<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627144556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cdr (id INT AUTO_INCREMENT NOT NULL, scheda_pai_id INT DEFAULT NULL, autore_cdr_id INT DEFAULT NULL, data_valutazione DATE NOT NULL, memoria VARCHAR(255) NOT NULL, orientamento VARCHAR(255) NOT NULL, giudizio VARCHAR(255) NOT NULL, attivita_sociali VARCHAR(255) NOT NULL, casa VARCHAR(255) NOT NULL, cura_personale VARCHAR(255) NOT NULL, INDEX IDX_A4A12D832524E3D (scheda_pai_id), INDEX IDX_A4A12D827C8C9AF (autore_cdr_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cdr ADD CONSTRAINT FK_A4A12D832524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cdr ADD CONSTRAINT FK_A4A12D827C8C9AF FOREIGN KEY (autore_cdr_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cdr DROP FOREIGN KEY FK_A4A12D832524E3D');
        $this->addSql('ALTER TABLE cdr DROP FOREIGN KEY FK_A4A12D827C8C9AF');
        $this->addSql('DROP TABLE cdr');
    }
}
