<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204154842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuisine_type DROP FOREIGN KEY FK_147D9462B1E7706E');
        $this->addSql('DROP INDEX IDX_147D9462B1E7706E ON cuisine_type');
        $this->addSql('ALTER TABLE cuisine_type DROP restaurant_id');
        $this->addSql('ALTER TABLE restaurant ADD cuisine_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F168B98F5 FOREIGN KEY (cuisine_type_id) REFERENCES cuisine_type (id)');
        $this->addSql('CREATE INDEX IDX_EB95123F168B98F5 ON restaurant (cuisine_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuisine_type ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cuisine_type ADD CONSTRAINT FK_147D9462B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_147D9462B1E7706E ON cuisine_type (restaurant_id)');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F168B98F5');
        $this->addSql('DROP INDEX IDX_EB95123F168B98F5 ON restaurant');
        $this->addSql('ALTER TABLE restaurant DROP cuisine_type_id');
    }
}
