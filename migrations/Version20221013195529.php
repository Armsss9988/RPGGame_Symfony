<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013195529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON game_type');
        $this->addSql('ALTER TABLE game_type ADD game_id INT NOT NULL, ADD category_id INT NOT NULL, DROP game, DROP category');
        $this->addSql('ALTER TABLE game_type ADD CONSTRAINT FK_67CB3B05E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game_type ADD CONSTRAINT FK_67CB3B0512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_67CB3B05E48FD905 ON game_type (game_id)');
        $this->addSql('CREATE INDEX IDX_67CB3B0512469DE2 ON game_type (category_id)');
        $this->addSql('ALTER TABLE game_type ADD PRIMARY KEY (game_id, category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type DROP FOREIGN KEY FK_67CB3B05E48FD905');
        $this->addSql('ALTER TABLE game_type DROP FOREIGN KEY FK_67CB3B0512469DE2');
        $this->addSql('DROP INDEX IDX_67CB3B05E48FD905 ON game_type');
        $this->addSql('DROP INDEX IDX_67CB3B0512469DE2 ON game_type');
        $this->addSql('DROP INDEX `PRIMARY` ON game_type');
        $this->addSql('ALTER TABLE game_type ADD game INT NOT NULL, ADD category INT NOT NULL, DROP game_id, DROP category_id');
        $this->addSql('ALTER TABLE game_type ADD PRIMARY KEY (game, category)');
    }
}
