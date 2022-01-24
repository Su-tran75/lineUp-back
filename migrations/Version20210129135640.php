<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129135640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant DROP user_id, DROP ticket_id, DROP cuisinetype_id');
        $this->addSql('ALTER TABLE ticket CHANGE restaurant_id restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP ticket_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant ADD user_id INT DEFAULT NULL, ADD ticket_id INT DEFAULT NULL, ADD cuisinetype_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket CHANGE restaurant_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD ticket_id INT DEFAULT NULL');
    }
}
