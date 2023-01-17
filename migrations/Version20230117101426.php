<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117101426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD autore_barthel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel ADD CONSTRAINT FK_90EA910F7A387E36 FOREIGN KEY (autore_barthel_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_90EA910F7A387E36 ON SCHEDA_PAI_barthel (autore_barthel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP FOREIGN KEY FK_90EA910F7A387E36');
        $this->addSql('DROP INDEX IDX_90EA910F7A387E36 ON SCHEDA_PAI_barthel');
        $this->addSql('ALTER TABLE SCHEDA_PAI_barthel DROP autore_barthel_id');
    }
}
