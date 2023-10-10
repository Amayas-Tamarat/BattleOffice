<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010143310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD country_id INT NOT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BEF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_5CECC7BEF92F3E70 ON adress (country_id)');
        $this->addSql('ALTER TABLE commande ADD payment_id INT DEFAULT NULL, ADD client_id INT NOT NULL, ADD shipping_adress_id INT DEFAULT NULL, ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC273A89B FOREIGN KEY (shipping_adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6EEAA67D4C3A3BB ON commande (payment_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D19EB6921 ON commande (client_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DC273A89B ON commande (shipping_adress_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DF347EFB ON commande (produit_id)');
        $this->addSql('ALTER TABLE payment ADD billing_adress_id INT DEFAULT NULL, ADD payment_method_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D30959BF2 FOREIGN KEY (billing_adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D5AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D30959BF2 ON payment (billing_adress_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D5AA1164F ON payment (payment_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BEF92F3E70');
        $this->addSql('DROP INDEX IDX_5CECC7BEF92F3E70 ON adress');
        $this->addSql('ALTER TABLE adress DROP country_id');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D4C3A3BB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC273A89B');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF347EFB');
        $this->addSql('DROP INDEX UNIQ_6EEAA67D4C3A3BB ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67D19EB6921 ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DC273A89B ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DF347EFB ON commande');
        $this->addSql('ALTER TABLE commande DROP payment_id, DROP client_id, DROP shipping_adress_id, DROP produit_id');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D30959BF2');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D5AA1164F');
        $this->addSql('DROP INDEX IDX_6D28840D30959BF2 ON payment');
        $this->addSql('DROP INDEX IDX_6D28840D5AA1164F ON payment');
        $this->addSql('ALTER TABLE payment DROP billing_adress_id, DROP payment_method_id');
    }
}
