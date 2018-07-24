<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180723143458 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ads ADD category_id INT NOT NULL, CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F62012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7EC9F62012469DE2 ON ads (category_id)');
        $this->addSql('ALTER TABLE photo CHANGE description description VARCHAR(150) DEFAULT NULL');
    }




    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F62012469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_7EC9F62012469DE2 ON ads');
        $this->addSql('ALTER TABLE ads ADD category VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, DROP category_id, CHANGE description description MEDIUMTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE photo CHANGE description description TEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
