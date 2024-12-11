<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211115752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_notification (id SERIAL NOT NULL, task_id INT NOT NULL, message VARCHAR(255) NOT NULL, trigger_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, notification_type VARCHAR(255) NOT NULL, is_sent BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_77C552E78DB60186 ON task_notification (task_id)');
        $this->addSql('ALTER TABLE task_notification ADD CONSTRAINT FK_77C552E78DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25A76ED395');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task_notification DROP CONSTRAINT FK_77C552E78DB60186');
        $this->addSql('DROP TABLE task_notification');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk_527edb25a76ed395');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT fk_527edb25a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
