<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190824234422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cleaner DROP INDEX UNIQ_6E8447A412469DE2, ADD INDEX IDX_6E8447A412469DE2 (category_id)');
        $this->addSql('ALTER TABLE cleaner CHANGE category_id category_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cleaner DROP INDEX IDX_6E8447A412469DE2, ADD UNIQUE INDEX UNIQ_6E8447A412469DE2 (category_id)');
        $this->addSql('ALTER TABLE cleaner CHANGE category_id category_id INT NOT NULL');
    }
}
