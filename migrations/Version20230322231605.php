<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322231605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scolarite (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, total DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_276250ABA6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tranche (id INT AUTO_INCREMENT NOT NULL, scolarite_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_66675840AA6B2AB6 (scolarite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT FK_276250ABA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_66675840AA6B2AB6 FOREIGN KEY (scolarite_id) REFERENCES scolarite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_66675840AA6B2AB6');
        $this->addSql('DROP TABLE scolarite');
        $this->addSql('DROP TABLE tranche');
    }
}
