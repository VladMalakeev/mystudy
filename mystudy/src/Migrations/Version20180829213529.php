<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180829213529 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department ADD institute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A697B0F4C FOREIGN KEY (institute_id) REFERENCES institute (id)');
        $this->addSql('CREATE INDEX IDX_CD1DE18A697B0F4C ON department (institute_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A697B0F4C');
        $this->addSql('DROP INDEX IDX_CD1DE18A697B0F4C ON department');
        $this->addSql('ALTER TABLE department DROP institute_id');
    }
}
