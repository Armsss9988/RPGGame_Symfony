<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013195314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE game_type DROP FOREIGN KEY FK_67CB3B05E48FD905');
        $this->addSql('DROP INDEX IDX_67CB3B05E48FD905 ON game_type');
        $this->addSql('DROP INDEX `primary` ON game_type');
        $this->addSql('ALTER TABLE game_type ADD game INT NOT NULL, DROP id, DROP game_id');
        $this->addSql('ALTER TABLE game_type ADD PRIMARY KEY (game)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type ADD id INT AUTO_INCREMENT NOT NULL, ADD game_id INT DEFAULT NULL, DROP game, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE game_type ADD CONSTRAINT FK_67CB3B05E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_67CB3B05E48FD905 ON game_type (game_id)');
    }
}
