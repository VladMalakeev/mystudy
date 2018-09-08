<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180828115916 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, subject_name_id INT DEFAULT NULL, lecturer_id INT DEFAULT NULL, groups_id INT DEFAULT NULL, department_id INT DEFAULT NULL, classroom VARCHAR(10) DEFAULT NULL, lesson_number INT DEFAULT NULL, day_of_week INT DEFAULT NULL, week INT DEFAULT NULL, INDEX IDX_5A3811FBD4953074 (subject_name_id), INDEX IDX_5A3811FBBA2D8762 (lecturer_id), INDEX IDX_5A3811FBF373DCF (groups_id), INDEX IDX_5A3811FBAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBD4953074 FOREIGN KEY (subject_name_id) REFERENCES subjects (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBBA2D8762 FOREIGN KEY (lecturer_id) REFERENCES lecturers (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBF373DCF FOREIGN KEY (groups_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE schedule');
    }
}
