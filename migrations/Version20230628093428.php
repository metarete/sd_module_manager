<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628093428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cdr CHANGE memoria memoria VARCHAR(255) DEFAULT NULL, CHANGE orientamento orientamento VARCHAR(255) DEFAULT NULL, CHANGE giudizio giudizio VARCHAR(255) DEFAULT NULL, CHANGE attivita_sociali attivita_sociali VARCHAR(255) DEFAULT NULL, CHANGE casa casa VARCHAR(255) DEFAULT NULL, CHANGE cura_personale cura_personale VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cdr CHANGE memoria memoria VARCHAR(255) NOT NULL, CHANGE orientamento orientamento VARCHAR(255) NOT NULL, CHANGE giudizio giudizio VARCHAR(255) NOT NULL, CHANGE attivita_sociali attivita_sociali VARCHAR(255) NOT NULL, CHANGE casa casa VARCHAR(255) NOT NULL, CHANGE cura_personale cura_personale VARCHAR(255) NOT NULL');
    }
}
