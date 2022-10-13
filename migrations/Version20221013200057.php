<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013200057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type ADD id INT AUTO_INCREMENT NOT NULL, CHANGE game_id game_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON game_type');
        $this->addSql('ALTER TABLE game_type DROP id, CHANGE game_id game_id INT NOT NULL, CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_type ADD PRIMARY KEY (game_id, category_id)');
    }
}
