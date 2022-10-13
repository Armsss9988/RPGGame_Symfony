<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013185507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type DROP INDEX UNIQ_67CB3B05E48FD905, ADD INDEX IDX_67CB3B05E48FD905 (game_id)');
        $this->addSql('ALTER TABLE game_type DROP INDEX UNIQ_67CB3B0512469DE2, ADD INDEX IDX_67CB3B0512469DE2 (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_type DROP INDEX IDX_67CB3B05E48FD905, ADD UNIQUE INDEX UNIQ_67CB3B05E48FD905 (game_id)');
        $this->addSql('ALTER TABLE game_type DROP INDEX IDX_67CB3B0512469DE2, ADD UNIQUE INDEX UNIQ_67CB3B0512469DE2 (category_id)');
    }
}
