<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622144933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_status_history (id SERIAL NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_44EFD897A76ED395 ON user_status_history (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_verification_requests (id SERIAL NOT NULL, user_id INT NOT NULL, code VARCHAR(10) NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A4E133DA76ED395 ON user_verification_requests (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN user_verification_requests.sent_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id SERIAL NOT NULL, user_name VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, registered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1483A5E96B01BC5B ON users (phone_number)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_status_history ADD CONSTRAINT FK_44EFD897A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_verification_requests ADD CONSTRAINT FK_4A4E133DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_status_history DROP CONSTRAINT FK_44EFD897A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_verification_requests DROP CONSTRAINT FK_4A4E133DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_status_history
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_verification_requests
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
    }
}
