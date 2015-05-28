<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150528115143 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_product_associations (product_id INT NOT NULL, association_id INT NOT NULL, INDEX IDX_5A425CA64584665A (product_id), UNIQUE INDEX UNIQ_5A425CA6EFB9C8A5 (association_id), PRIMARY KEY(product_id, association_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_association_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_association (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, associated_product_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, association_to VARCHAR(255) NOT NULL, INDEX IDX_5AC75835C54C8C93 (type_id), INDEX IDX_5AC75835AE33471B (associated_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_product_associations ADD CONSTRAINT FK_5A425CA64584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE sylius_product_associations ADD CONSTRAINT FK_5A425CA6EFB9C8A5 FOREIGN KEY (association_id) REFERENCES sylius_association (id)');
        $this->addSql('ALTER TABLE sylius_association ADD CONSTRAINT FK_5AC75835C54C8C93 FOREIGN KEY (type_id) REFERENCES sylius_association_type (id)');
        $this->addSql('ALTER TABLE sylius_association ADD CONSTRAINT FK_5AC75835AE33471B FOREIGN KEY (associated_product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX fulltext_search_idx ON sylius_search_index');
        $this->addSql('CREATE INDEX fulltext_search_idx ON sylius_search_index (item_id)');
        $this->addSql('ALTER TABLE phpcr_nodes ADD numerical_props LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_association DROP FOREIGN KEY FK_5AC75835C54C8C93');
        $this->addSql('ALTER TABLE sylius_product_associations DROP FOREIGN KEY FK_5A425CA6EFB9C8A5');
        $this->addSql('DROP TABLE sylius_product_associations');
        $this->addSql('DROP TABLE sylius_association_type');
        $this->addSql('DROP TABLE sylius_association');
        $this->addSql('ALTER TABLE phpcr_nodes DROP numerical_props');
        $this->addSql('DROP INDEX fulltext_search_idx ON sylius_search_index');
        $this->addSql('CREATE FULLTEXT INDEX fulltext_search_idx ON sylius_search_index (value)');
    }
}
