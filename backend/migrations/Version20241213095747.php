<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213095747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE task_notification (id SERIAL NOT NULL, task_id INT NOT NULL, message VARCHAR(255) NOT NULL, trigger_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, notification_type VARCHAR(255) NOT NULL, is_sent BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_77C552E78DB60186 ON task_notification (task_id)');
        $this->addSql('ALTER TABLE task_notification ADD CONSTRAINT FK_77C552E78DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE task_notification');
    }
}
