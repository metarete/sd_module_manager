<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206083838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP FOREIGN KEY FK_90EA910F32524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD CONSTRAINT FK_90EA910F32524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP FOREIGN KEY FK_90EA910F32524E3D');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD CONSTRAINT FK_90EA910F32524E3D FOREIGN KEY (scheda_pai_id) REFERENCES SCHEDA_PAI (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
