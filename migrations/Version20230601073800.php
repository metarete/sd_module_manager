<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601073800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesioni_condizione_lesione (lesioni_id INT NOT NULL, condizione_lesione_id INT NOT NULL, INDEX IDX_C8EF82EC2DA072E (lesioni_id), INDEX IDX_C8EF82ECCA9DBBC0 (condizione_lesione_id), PRIMARY KEY(lesioni_id, condizione_lesione_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesioni_bordi_lesione (lesioni_id INT NOT NULL, bordi_lesione_id INT NOT NULL, INDEX IDX_633C85932DA072E (lesioni_id), INDEX IDX_633C8593749C0D74 (bordi_lesione_id), PRIMARY KEY(lesioni_id, bordi_lesione_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesioni_cute_perilesionale (lesioni_id INT NOT NULL, cute_perilesionale_id INT NOT NULL, INDEX IDX_F397F5822DA072E (lesioni_id), INDEX IDX_F397F582B6744BEE (cute_perilesionale_id), PRIMARY KEY(lesioni_id, cute_perilesionale_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesioni_medicazione (lesioni_id INT NOT NULL, medicazione_id INT NOT NULL, INDEX IDX_7C235F812DA072E (lesioni_id), INDEX IDX_7C235F81E1A3D178 (medicazione_id), PRIMARY KEY(lesioni_id, medicazione_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesioni_copertura (lesioni_id INT NOT NULL, copertura_id INT NOT NULL, INDEX IDX_187BBE1B2DA072E (lesioni_id), INDEX IDX_187BBE1B33BD9A56 (copertura_id), PRIMARY KEY(lesioni_id, copertura_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bordi_lesione (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE condizione_lesione (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE copertura (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cute_perilesionale (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicazione (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesioni_condizione_lesione ADD CONSTRAINT FK_C8EF82EC2DA072E FOREIGN KEY (lesioni_id) REFERENCES SCHEDA_PAI_lesioni (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_condizione_lesione ADD CONSTRAINT FK_C8EF82ECCA9DBBC0 FOREIGN KEY (condizione_lesione_id) REFERENCES condizione_lesione (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_bordi_lesione ADD CONSTRAINT FK_633C85932DA072E FOREIGN KEY (lesioni_id) REFERENCES SCHEDA_PAI_lesioni (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_bordi_lesione ADD CONSTRAINT FK_633C8593749C0D74 FOREIGN KEY (bordi_lesione_id) REFERENCES bordi_lesione (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_cute_perilesionale ADD CONSTRAINT FK_F397F5822DA072E FOREIGN KEY (lesioni_id) REFERENCES SCHEDA_PAI_lesioni (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_cute_perilesionale ADD CONSTRAINT FK_F397F582B6744BEE FOREIGN KEY (cute_perilesionale_id) REFERENCES cute_perilesionale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_medicazione ADD CONSTRAINT FK_7C235F812DA072E FOREIGN KEY (lesioni_id) REFERENCES SCHEDA_PAI_lesioni (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_medicazione ADD CONSTRAINT FK_7C235F81E1A3D178 FOREIGN KEY (medicazione_id) REFERENCES medicazione (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_copertura ADD CONSTRAINT FK_187BBE1B2DA072E FOREIGN KEY (lesioni_id) REFERENCES SCHEDA_PAI_lesioni (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesioni_copertura ADD CONSTRAINT FK_187BBE1B33BD9A56 FOREIGN KEY (copertura_id) REFERENCES copertura (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni DROP condizione_lesione, DROP bordi_lesione, DROP cute_perilesionale');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesioni_condizione_lesione DROP FOREIGN KEY FK_C8EF82EC2DA072E');
        $this->addSql('ALTER TABLE lesioni_condizione_lesione DROP FOREIGN KEY FK_C8EF82ECCA9DBBC0');
        $this->addSql('ALTER TABLE lesioni_bordi_lesione DROP FOREIGN KEY FK_633C85932DA072E');
        $this->addSql('ALTER TABLE lesioni_bordi_lesione DROP FOREIGN KEY FK_633C8593749C0D74');
        $this->addSql('ALTER TABLE lesioni_cute_perilesionale DROP FOREIGN KEY FK_F397F5822DA072E');
        $this->addSql('ALTER TABLE lesioni_cute_perilesionale DROP FOREIGN KEY FK_F397F582B6744BEE');
        $this->addSql('ALTER TABLE lesioni_medicazione DROP FOREIGN KEY FK_7C235F812DA072E');
        $this->addSql('ALTER TABLE lesioni_medicazione DROP FOREIGN KEY FK_7C235F81E1A3D178');
        $this->addSql('ALTER TABLE lesioni_copertura DROP FOREIGN KEY FK_187BBE1B2DA072E');
        $this->addSql('ALTER TABLE lesioni_copertura DROP FOREIGN KEY FK_187BBE1B33BD9A56');
        $this->addSql('DROP TABLE lesioni_condizione_lesione');
        $this->addSql('DROP TABLE lesioni_bordi_lesione');
        $this->addSql('DROP TABLE lesioni_cute_perilesionale');
        $this->addSql('DROP TABLE lesioni_medicazione');
        $this->addSql('DROP TABLE lesioni_copertura');
        $this->addSql('DROP TABLE bordi_lesione');
        $this->addSql('DROP TABLE condizione_lesione');
        $this->addSql('DROP TABLE copertura');
        $this->addSql('DROP TABLE cute_perilesionale');
        $this->addSql('DROP TABLE medicazione');
        $this->addSql('ALTER TABLE SCHEDA_PAI_lesioni ADD condizione_lesione VARCHAR(255) NOT NULL, ADD bordi_lesione VARCHAR(255) NOT NULL, ADD cute_perilesionale VARCHAR(255) NOT NULL');
    }
}
