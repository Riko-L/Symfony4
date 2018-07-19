<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180719122747 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ads (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(150) NOT NULL, description VARCHAR(150) NOT NULL, creation_date DATE NOT NULL, region VARCHAR(150) NOT NULL, category VARCHAR(150) NOT NULL, INDEX IDX_7EC9F620F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, ads_id INT NOT NULL, name VARCHAR(150) NOT NULL, description VARCHAR(150) DEFAULT NULL, path LONGTEXT NOT NULL, INDEX IDX_14B78418FE52BF81 (ads_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620F675F31B FOREIGN KEY (author_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418FE52BF81 FOREIGN KEY (ads_id) REFERENCES ads (id)');
        $this->addSql('ALTER TABLE person CHANGE first_name first_name VARCHAR(191) NOT NULL, CHANGE last_name last_name VARCHAR(191) NOT NULL, CHANGE phone_number phone_number VARCHAR(191) NOT NULL, CHANGE email email VARCHAR(191) NOT NULL, CHANGE username username VARCHAR(191) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418FE52BF81');
        $this->addSql('DROP TABLE ads');
        $this->addSql('DROP TABLE photo');
        $this->addSql('ALTER TABLE person CHANGE email email VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE username username VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE first_name first_name VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone_number phone_number VARCHAR(191) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
