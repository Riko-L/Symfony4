<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Region;
use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724090819 extends AbstractMigration implements ContainerAwareInterface
{

    private $container;




    public function __construct(Version $version)
    {
        parent::__construct($version);


    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }



    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ads ADD region_id INT DEFAULT NULL, CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F62098260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_7EC9F62098260155 ON ads (region_id)');
    }

    public function postUp(Schema $schema )
    {
        parent::postUp($schema);

        $response = json_decode(file_get_contents('https://geo.api.gouv.fr/regions?fields=nom'),true);


        $myRegions  = array_map( function($el) {

            return $el['nom'];

        }, $response);


        foreach($myRegions as $region) {

            $rg = new Region();

            $rg->setName($region);
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($rg);
            $em->flush();


        }

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F62098260155');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP INDEX IDX_7EC9F62098260155 ON ads');
        $this->addSql('ALTER TABLE ads DROP region_id, CHANGE description description MEDIUMTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
    }


}
