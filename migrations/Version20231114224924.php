<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114224924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD address_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81589BE29 FOREIGN KEY (address_category_id) REFERENCES address_category (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F81589BE29 ON address (address_category_id)');
        $this->addSql('ALTER TABLE line_cart ADD cart_id INT NOT NULL');
        $this->addSql('ALTER TABLE line_cart ADD CONSTRAINT FK_80EE7B6E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_80EE7B6E1AD5CDBF ON line_cart (cart_id)');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C6BDFEB');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398EBF23851');
        $this->addSql('DROP INDEX IDX_F5299398C6BDFEB ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398EBF23851 ON `order`');
        $this->addSql('ALTER TABLE `order` ADD address_id INT NOT NULL, DROP delivery_address_id, DROP invoice_address_id');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_F5299398F5B7AF75 ON `order` (address_id)');
        $this->addSql('ALTER TABLE returned_product CHANGE date returned_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81589BE29');
        $this->addSql('DROP INDEX IDX_D4E6F81589BE29 ON address');
        $this->addSql('ALTER TABLE address DROP address_category_id');
        $this->addSql('ALTER TABLE line_cart DROP FOREIGN KEY FK_80EE7B6E1AD5CDBF');
        $this->addSql('DROP INDEX IDX_80EE7B6E1AD5CDBF ON line_cart');
        $this->addSql('ALTER TABLE line_cart DROP cart_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398F5B7AF75');
        $this->addSql('DROP INDEX IDX_F5299398F5B7AF75 ON `order`');
        $this->addSql('ALTER TABLE `order` ADD invoice_address_id INT NOT NULL, CHANGE address_id delivery_address_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C6BDFEB FOREIGN KEY (invoice_address_id) REFERENCES address (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398EBF23851 FOREIGN KEY (delivery_address_id) REFERENCES address (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398C6BDFEB ON `order` (invoice_address_id)');
        $this->addSql('CREATE INDEX IDX_F5299398EBF23851 ON `order` (delivery_address_id)');
        $this->addSql('ALTER TABLE returned_product CHANGE returned_date date DATETIME NOT NULL');
    }
}
