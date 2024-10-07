<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007122014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cover_letter (id INT AUTO_INCREMENT NOT NULL, job_offer_id INT DEFAULT NULL, app_user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EBE6B473481D195 (job_offer_id), INDEX IDX_EBE6B474A3353D8 (app_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, app_user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, company VARCHAR(150) NOT NULL, link VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, salary VARCHAR(50) DEFAULT NULL, contact_person VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, application_date DATE NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_288A3A4E4A3353D8 (app_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE linked_in_message (id INT AUTO_INCREMENT NOT NULL, job_offer_id INT DEFAULT NULL, app_user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6ACAC8D63481D195 (job_offer_id), INDEX IDX_6ACAC8D64A3353D8 (app_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(80) NOT NULL, last_name VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cover_letter ADD CONSTRAINT FK_EBE6B473481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('ALTER TABLE cover_letter ADD CONSTRAINT FK_EBE6B474A3353D8 FOREIGN KEY (app_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E4A3353D8 FOREIGN KEY (app_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE linked_in_message ADD CONSTRAINT FK_6ACAC8D63481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('ALTER TABLE linked_in_message ADD CONSTRAINT FK_6ACAC8D64A3353D8 FOREIGN KEY (app_user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover_letter DROP FOREIGN KEY FK_EBE6B473481D195');
        $this->addSql('ALTER TABLE cover_letter DROP FOREIGN KEY FK_EBE6B474A3353D8');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E4A3353D8');
        $this->addSql('ALTER TABLE linked_in_message DROP FOREIGN KEY FK_6ACAC8D63481D195');
        $this->addSql('ALTER TABLE linked_in_message DROP FOREIGN KEY FK_6ACAC8D64A3353D8');
        $this->addSql('DROP TABLE cover_letter');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE linked_in_message');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
