<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328125736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP deambulazione');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas DROP ora');
        $this->addSql('ALTER TABLE user ADD cf VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD deambulazione TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_vas ADD ora TIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP cf');
    }
}
