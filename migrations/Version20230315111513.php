<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315111513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE filiere (id INT AUTO_INCREMENT NOT NULL, salle_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, effectif INT NOT NULL, UNIQUE INDEX UNIQ_2ED05D9EDC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_9014574AE455FCC0 (enseignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_filiere (matiere_id INT NOT NULL, filiere_id INT NOT NULL, INDEX IDX_4DF6D262F46CD258 (matiere_id), INDEX IDX_4DF6D262180AA129 (filiere_id), PRIMARY KEY(matiere_id, filiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, eleve_id INT NOT NULL, note DOUBLE PRECISION NOT NULL, INDEX IDX_CFBDFA14F46CD258 (matiere_id), INDEX IDX_CFBDFA14A6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, nbre_place INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, filiere_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, contact VARCHAR(255) DEFAULT NULL, date_naiss DATETIME NOT NULL, adresse VARCHAR(255) DEFAULT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649180AA129 (filiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filiere ADD CONSTRAINT FK_2ED05D9EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE matiere_filiere ADD CONSTRAINT FK_4DF6D262F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_filiere ADD CONSTRAINT FK_4DF6D262180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_filiere DROP FOREIGN KEY FK_4DF6D262180AA129');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649180AA129');
        $this->addSql('ALTER TABLE matiere_filiere DROP FOREIGN KEY FK_4DF6D262F46CD258');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14F46CD258');
        $this->addSql('ALTER TABLE filiere DROP FOREIGN KEY FK_2ED05D9EDC304035');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AE455FCC0');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A6CC7B2');
        $this->addSql('DROP TABLE filiere');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_filiere');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
