<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210809082923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD COLUMN quantity INTEGER Null ');
        $this->addSql('DROP INDEX IDX_CDFC73564584665A');
        $this->addSql('DROP INDEX IDX_CDFC735612469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_category AS SELECT product_id, category_id FROM product_category');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('CREATE TABLE product_category (product_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(product_id, category_id), CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_category (product_id, category_id) SELECT product_id, category_id FROM __temp__product_category');
        $this->addSql('DROP TABLE __temp__product_category');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, username, password, role FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL COLLATE BINARY, username VARCHAR(255) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, role VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, username, password, role) SELECT id, email, username, password, role FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, price, description, content, image, promo FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INTEGER NOT NULL, description VARCHAR(255) NOT NULL, content CLOB NOT NULL, image VARCHAR(255) NOT NULL, promo BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO product (id, name, price, description, content, image, promo) SELECT id, name, price, description, content, image, promo FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('DROP INDEX IDX_CDFC73564584665A');
        $this->addSql('DROP INDEX IDX_CDFC735612469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_category AS SELECT product_id, category_id FROM product_category');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('CREATE TABLE product_category (product_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(product_id, category_id))');
        $this->addSql('INSERT INTO product_category (product_id, category_id) SELECT product_id, category_id FROM __temp__product_category');
        $this->addSql('DROP TABLE __temp__product_category');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, username, password, role FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO user (id, email, username, password, role) SELECT id, email, username, password, role FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
