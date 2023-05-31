<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531123449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE braden_presidi_antidecubito (braden_id INT NOT NULL, presidi_antidecubito_id INT NOT NULL, INDEX IDX_CF5B6CB3D7664553 (braden_id), INDEX IDX_CF5B6CB3D2CBAAB5 (presidi_antidecubito_id), PRIMARY KEY(braden_id, presidi_antidecubito_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE braden_presidi_antidecubito ADD CONSTRAINT FK_CF5B6CB3D7664553 FOREIGN KEY (braden_id) REFERENCES SCHEDA_PAI_braden (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE braden_presidi_antidecubito ADD CONSTRAINT FK_CF5B6CB3D2CBAAB5 FOREIGN KEY (presidi_antidecubito_id) REFERENCES presidi_antidecubito (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE presidi_antidecubito_braden DROP FOREIGN KEY FK_6477B6B9D2CBAAB5');
        $this->addSql('ALTER TABLE presidi_antidecubito_braden DROP FOREIGN KEY FK_6477B6B9D7664553');
        $this->addSql('DROP TABLE presidi_antidecubito_braden');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE presidi_antidecubito_braden (presidi_antidecubito_id INT NOT NULL, braden_id INT NOT NULL, INDEX IDX_6477B6B9D7664553 (braden_id), INDEX IDX_6477B6B9D2CBAAB5 (presidi_antidecubito_id), PRIMARY KEY(presidi_antidecubito_id, braden_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE presidi_antidecubito_braden ADD CONSTRAINT FK_6477B6B9D2CBAAB5 FOREIGN KEY (presidi_antidecubito_id) REFERENCES presidi_antidecubito (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE presidi_antidecubito_braden ADD CONSTRAINT FK_6477B6B9D7664553 FOREIGN KEY (braden_id) REFERENCES SCHEDA_PAI_braden (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE braden_presidi_antidecubito DROP FOREIGN KEY FK_CF5B6CB3D7664553');
        $this->addSql('ALTER TABLE braden_presidi_antidecubito DROP FOREIGN KEY FK_CF5B6CB3D2CBAAB5');
        $this->addSql('DROP TABLE braden_presidi_antidecubito');
    }
}
