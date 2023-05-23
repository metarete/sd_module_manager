<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523090031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale CHANGE fanf fanf ENUM(\'nessuna\', \'presenza 24h su 24\', \'presenza saltuaria a ore nell arco della settimana\', \'solo giorni feriali\') NOT NULL COMMENT \'(DC2Type:FANF)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI_valutazione_generale CHANGE fanf fanf ENUM(\'nessuna\', \'presenza 24h su 24\', \'presenza saltuaria a ore nell arco della settimana\', \'solo giorni feriali\') DEFAULT NULL COMMENT \'(DC2Type:FANF)\'');
    }
}
