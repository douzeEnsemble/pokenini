<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231105910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trainer_pokemon_elo (elo INT NOT NULL, trainer_external_id VARCHAR(255) NOT NULL, election_slug VARCHAR(255) NOT NULL, id UUID NOT NULL, pokemon_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C01A9E772FE71C3E ON trainer_pokemon_elo (pokemon_id)');
        $this->addSql('CREATE TABLE trainer_vote (created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, trainer_external_id VARCHAR(255) NOT NULL, election_slug VARCHAR(255) NOT NULL, winner_slug VARCHAR(255) NOT NULL, losers TEXT NOT NULL, id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE trainer_pokemon_elo ADD CONSTRAINT FK_C01A9E772FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainer_pokemon_elo DROP CONSTRAINT FK_C01A9E772FE71C3E');
        $this->addSql('DROP TABLE trainer_pokemon_elo');
        $this->addSql('DROP TABLE trainer_vote');
    }
}
