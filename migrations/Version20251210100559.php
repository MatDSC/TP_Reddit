<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251210100559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP upvotes, DROP downvotes');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY `FK_5A108564F8697D13`');
        $this->addSql('DROP INDEX IDX_5A108564F8697D13 ON vote');
        $this->addSql('ALTER TABLE vote ADD value INT NOT NULL, DROP type, DROP comment_id, CHANGE post_id post_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD upvotes INT NOT NULL, ADD downvotes INT NOT NULL');
        $this->addSql('ALTER TABLE vote ADD type VARCHAR(10) NOT NULL, ADD comment_id INT DEFAULT NULL, DROP value, CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT `FK_5A108564F8697D13` FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_5A108564F8697D13 ON vote (comment_id)');
    }
}
