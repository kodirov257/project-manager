<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106174349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_users ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_users ADD last_name VARCHAR(255) NOT NULL');

        $this->addSql('UPDATE user_users SET first_name =\'\'');
        $this->addSql('UPDATE user_users SET last_name =\'\'');

        $this->addSql('ALTER TABLE user_users ALTER first_name SET NOT NULL');
        $this->addSql('ALTER TABLE user_users ALTER last_name SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_users DROP first_name');
        $this->addSql('ALTER TABLE user_users DROP last_name');
    }
}
