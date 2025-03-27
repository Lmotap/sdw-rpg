<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325171335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ennemy (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, strength INT NOT NULL, constitution INT NOT NULL, health INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE character ADD class VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE character ADD xp INT NOT NULL');
        $this->addSql('ALTER TABLE character ADD health INT NOT NULL');
        $this->addSql('ALTER TABLE character ADD level INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE ennemy');
        $this->addSql('ALTER TABLE character DROP class');
        $this->addSql('ALTER TABLE character DROP xp');
        $this->addSql('ALTER TABLE character DROP health');
        $this->addSql('ALTER TABLE character DROP level');
    }
}
