<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829132932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_tag (project_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_91F26D60166D1F9C (project_id), INDEX IDX_91F26D60BAD26311 (tag_id), PRIMARY KEY(project_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_tag (student_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_95F4B225CB944F1A (student_id), INDEX IDX_95F4B225BAD26311 (tag_id), PRIMARY KEY(student_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_project (student_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_C2856516CB944F1A (student_id), INDEX IDX_C2856516166D1F9C (project_id), PRIMARY KEY(student_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_tag ADD CONSTRAINT FK_95F4B225CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_tag ADD CONSTRAINT FK_95F4B225BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_project ADD CONSTRAINT FK_C2856516CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_project ADD CONSTRAINT FK_C2856516166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project CHANGE client_name client_name VARCHAR(190) NOT NULL, CHANGE check_point_date checkpoint_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE school_year ADD name VARCHAR(190) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD start_date DATE DEFAULT NULL, ADD end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD first_name VARCHAR(190) NOT NULL, ADD last_name VARCHAR(190) NOT NULL');
        $this->addSql('ALTER TABLE user ADD student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CB944F1A ON user (student_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_91F26D60166D1F9C');
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_91F26D60BAD26311');
        $this->addSql('ALTER TABLE student_tag DROP FOREIGN KEY FK_95F4B225CB944F1A');
        $this->addSql('ALTER TABLE student_tag DROP FOREIGN KEY FK_95F4B225BAD26311');
        $this->addSql('ALTER TABLE student_project DROP FOREIGN KEY FK_C2856516CB944F1A');
        $this->addSql('ALTER TABLE student_project DROP FOREIGN KEY FK_C2856516166D1F9C');
        $this->addSql('DROP TABLE project_tag');
        $this->addSql('DROP TABLE student_tag');
        $this->addSql('DROP TABLE student_project');
        $this->addSql('ALTER TABLE school_year DROP name, DROP description, DROP start_date, DROP end_date');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CB944F1A');
        $this->addSql('DROP INDEX UNIQ_8D93D649CB944F1A ON user');
        $this->addSql('ALTER TABLE user DROP student_id');
        $this->addSql('ALTER TABLE student DROP first_name, DROP last_name');
        $this->addSql('ALTER TABLE project CHANGE client_name client_name VARCHAR(191) NOT NULL, CHANGE checkpoint_date check_point_date DATE DEFAULT NULL');
    }
}
