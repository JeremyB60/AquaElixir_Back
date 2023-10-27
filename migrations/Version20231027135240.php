<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027135240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_tag (product_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E3A6E39C4584665A (product_id), INDEX IDX_E3A6E39CBAD26311 (tag_id), PRIMARY KEY(product_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('DROP INDEX IDX_D4E6F81A76ED395 ON address');
        $this->addSql('ALTER TABLE address ADD user_address_id INT NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8152D06999 FOREIGN KEY (user_address_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F8152D06999 ON address (user_address_id)');
        $this->addSql('ALTER TABLE cart ADD cart_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B75CB41B92 FOREIGN KEY (cart_user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B75CB41B92 ON cart (cart_user_id)');
        $this->addSql('ALTER TABLE image ADD image_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F3DA5256D FOREIGN KEY (image_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F3DA5256D ON image (image_id)');
        $this->addSql('ALTER TABLE line_cart ADD cart_id INT NOT NULL, ADD line_cart_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE line_cart ADD CONSTRAINT FK_80EE7B6E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE line_cart ADD CONSTRAINT FK_80EE7B6E8B0B8AD0 FOREIGN KEY (line_cart_product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_80EE7B6E1AD5CDBF ON line_cart (cart_id)');
        $this->addSql('CREATE INDEX IDX_80EE7B6E8B0B8AD0 ON line_cart (line_cart_product_id)');
        $this->addSql('ALTER TABLE line_product ADD line_product_order_id INT NOT NULL, ADD product_line_id INT NOT NULL');
        $this->addSql('ALTER TABLE line_product ADD CONSTRAINT FK_66EE85CA185F1B3D FOREIGN KEY (line_product_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE line_product ADD CONSTRAINT FK_66EE85CA9CA26EF2 FOREIGN KEY (product_line_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_66EE85CA185F1B3D ON line_product (line_product_order_id)');
        $this->addSql('CREATE INDEX IDX_66EE85CA9CA26EF2 ON line_product (product_line_id)');
        $this->addSql('ALTER TABLE `order` ADD order_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939851147ADE FOREIGN KEY (order_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F529939851147ADE ON `order` (order_user_id)');
        $this->addSql('ALTER TABLE product ADD product_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD14959723 ON product (product_type_id)');
        $this->addSql('ALTER TABLE returned_product ADD order_returned_product_id INT NOT NULL, ADD product_returned_id INT NOT NULL');
        $this->addSql('ALTER TABLE returned_product ADD CONSTRAINT FK_AF6E52FE34DAD0BE FOREIGN KEY (order_returned_product_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE returned_product ADD CONSTRAINT FK_AF6E52FE2A482DBB FOREIGN KEY (product_returned_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_AF6E52FE34DAD0BE ON returned_product (order_returned_product_id)');
        $this->addSql('CREATE INDEX IDX_AF6E52FE2A482DBB ON returned_product (product_returned_id)');
        $this->addSql('ALTER TABLE review ADD product_review_id INT NOT NULL, ADD review_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6508E2016 FOREIGN KEY (product_review_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C63C806762 FOREIGN KEY (review_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C6508E2016 ON review (product_review_id)');
        $this->addSql('CREATE INDEX IDX_794381C63C806762 ON review (review_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39C4584665A');
        $this->addSql('ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39CBAD26311');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8152D06999');
        $this->addSql('DROP INDEX IDX_D4E6F8152D06999 ON address');
        $this->addSql('ALTER TABLE address ADD user_id INT DEFAULT NULL, DROP user_address_id');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)');
        $this->addSql('ALTER TABLE line_product DROP FOREIGN KEY FK_66EE85CA185F1B3D');
        $this->addSql('ALTER TABLE line_product DROP FOREIGN KEY FK_66EE85CA9CA26EF2');
        $this->addSql('DROP INDEX IDX_66EE85CA185F1B3D ON line_product');
        $this->addSql('DROP INDEX IDX_66EE85CA9CA26EF2 ON line_product');
        $this->addSql('ALTER TABLE line_product DROP line_product_order_id, DROP product_line_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939851147ADE');
        $this->addSql('DROP INDEX IDX_F529939851147ADE ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_user_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD14959723');
        $this->addSql('DROP INDEX IDX_D34A04AD14959723 ON product');
        $this->addSql('ALTER TABLE product DROP product_type_id');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F3DA5256D');
        $this->addSql('DROP INDEX IDX_C53D045F3DA5256D ON image');
        $this->addSql('ALTER TABLE image DROP image_id');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B75CB41B92');
        $this->addSql('DROP INDEX UNIQ_BA388B75CB41B92 ON cart');
        $this->addSql('ALTER TABLE cart DROP cart_user_id');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6508E2016');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C63C806762');
        $this->addSql('DROP INDEX IDX_794381C6508E2016 ON review');
        $this->addSql('DROP INDEX IDX_794381C63C806762 ON review');
        $this->addSql('ALTER TABLE review DROP product_review_id, DROP review_user_id');
        $this->addSql('ALTER TABLE line_cart DROP FOREIGN KEY FK_80EE7B6E1AD5CDBF');
        $this->addSql('ALTER TABLE line_cart DROP FOREIGN KEY FK_80EE7B6E8B0B8AD0');
        $this->addSql('DROP INDEX IDX_80EE7B6E1AD5CDBF ON line_cart');
        $this->addSql('DROP INDEX IDX_80EE7B6E8B0B8AD0 ON line_cart');
        $this->addSql('ALTER TABLE line_cart DROP cart_id, DROP line_cart_product_id');
        $this->addSql('ALTER TABLE returned_product DROP FOREIGN KEY FK_AF6E52FE34DAD0BE');
        $this->addSql('ALTER TABLE returned_product DROP FOREIGN KEY FK_AF6E52FE2A482DBB');
        $this->addSql('DROP INDEX IDX_AF6E52FE34DAD0BE ON returned_product');
        $this->addSql('DROP INDEX IDX_AF6E52FE2A482DBB ON returned_product');
        $this->addSql('ALTER TABLE returned_product DROP order_returned_product_id, DROP product_returned_id');
    }
}
