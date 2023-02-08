<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208140123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI DROP numero_barthel_ad_oggi, DROP numero_braden_ad_oggi, DROP numero_spmsq_ad_oggi, DROP numero_tinetti_ad_oggi, DROP numero_vas_ad_oggi, DROP numero_lesioni_ad_oggi, DROP numero_barthel_ad_oggi_corretto, DROP numero_braden_ad_oggi_corretto, DROP numero_spmsq_ad_oggi_corretto, DROP numero_tinetti_ad_oggi_corretto, DROP numero_vas_ad_oggi_corretto, DROP numero_lesioni_ad_oggi_corretto');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SCHEDA_PAI ADD numero_barthel_ad_oggi INT NOT NULL, ADD numero_braden_ad_oggi INT NOT NULL, ADD numero_spmsq_ad_oggi INT NOT NULL, ADD numero_tinetti_ad_oggi INT NOT NULL, ADD numero_vas_ad_oggi INT NOT NULL, ADD numero_lesioni_ad_oggi INT NOT NULL, ADD numero_barthel_ad_oggi_corretto INT NOT NULL, ADD numero_braden_ad_oggi_corretto INT NOT NULL, ADD numero_spmsq_ad_oggi_corretto INT NOT NULL, ADD numero_tinetti_ad_oggi_corretto INT NOT NULL, ADD numero_vas_ad_oggi_corretto INT NOT NULL, ADD numero_lesioni_ad_oggi_corretto INT NOT NULL');
    }
}
