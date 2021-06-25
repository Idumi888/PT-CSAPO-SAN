<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210625072237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE epreuve (id INT AUTO_INCREMENT NOT NULL, code_module INT NOT NULL, nom_module VARCHAR(100) NOT NULL, classe VARCHAR(50) NOT NULL, nombre_eleve INT NOT NULL, sujet VARCHAR(100) NOT NULL, duree INT NOT NULL, date_epreuve DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE epreuve_utilisateur (epreuve_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_B928A63FAB990336 (epreuve_id), INDEX IDX_B928A63FFB88E14F (utilisateur_id), PRIMARY KEY(epreuve_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passe (id INT AUTO_INCREMENT NOT NULL, epreuve_id INT DEFAULT NULL, eleve_id INT DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D317EE5FAB990336 (epreuve_id), INDEX IDX_D317EE5FA6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE epreuve_utilisateur ADD CONSTRAINT FK_B928A63FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epreuve_utilisateur ADD CONSTRAINT FK_B928A63FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id)');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FA6CC7B2');
        $this->addSql('ALTER TABLE epreuve_utilisateur DROP FOREIGN KEY FK_B928A63FAB990336');
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FAB990336');
        $this->addSql('ALTER TABLE epreuve_utilisateur DROP FOREIGN KEY FK_B928A63FFB88E14F');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE epreuve');
        $this->addSql('DROP TABLE epreuve_utilisateur');
        $this->addSql('DROP TABLE passe');
        $this->addSql('DROP TABLE utilisateur');
    }
}
