<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180824200408 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lecturers ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lecturers_department ADD PRIMARY KEY (lecturers_id, department_id)');
        $this->addSql('ALTER TABLE lecturers_department ADD CONSTRAINT FK_F32663D0447790D6 FOREIGN KEY (lecturers_id) REFERENCES lecturers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecturers_department ADD CONSTRAINT FK_F32663D0AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F32663D0447790D6 ON lecturers_department (lecturers_id)');
        $this->addSql('CREATE INDEX IDX_F32663D0AE80F5DF ON lecturers_department (department_id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBBA2D8762 FOREIGN KEY (lecturer_id) REFERENCES lecturers (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lecturers DROP photo');
        $this->addSql('ALTER TABLE lecturers_department DROP FOREIGN KEY FK_F32663D0447790D6');
        $this->addSql('ALTER TABLE lecturers_department DROP FOREIGN KEY FK_F32663D0AE80F5DF');
        $this->addSql('DROP INDEX IDX_F32663D0447790D6 ON lecturers_department');
        $this->addSql('DROP INDEX IDX_F32663D0AE80F5DF ON lecturers_department');
        $this->addSql('ALTER TABLE lecturers_department DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBBA2D8762');
    }
}
