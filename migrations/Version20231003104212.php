<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003104212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audio_privacy (id INT AUTO_INCREMENT NOT NULL, assistito_id INT DEFAULT NULL, audio LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_FC3310D4A1485A3F (assistito_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE audio_privacy ADD CONSTRAINT FK_FC3310D4A1485A3F FOREIGN KEY (assistito_id) REFERENCES paziente (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audio_privacy DROP FOREIGN KEY FK_FC3310D4A1485A3F');
        $this->addSql('DROP TABLE audio_privacy');
    }
}
