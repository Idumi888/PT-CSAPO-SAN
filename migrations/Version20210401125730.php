<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401125730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE epreuve_utilisateur (epreuve_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_B928A63FAB990336 (epreuve_id), INDEX IDX_B928A63FFB88E14F (utilisateur_id), PRIMARY KEY(epreuve_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE epreuve_utilisateur ADD CONSTRAINT FK_B928A63FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epreuve_utilisateur ADD CONSTRAINT FK_B928A63FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE passe ADD epreuve_id INT DEFAULT NULL, ADD eleve_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id)');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('CREATE INDEX IDX_D317EE5FAB990336 ON passe (epreuve_id)');
        $this->addSql('CREATE INDEX IDX_D317EE5FA6CC7B2 ON passe (eleve_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE epreuve_utilisateur');
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FAB990336');
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FA6CC7B2');
        $this->addSql('DROP INDEX IDX_D317EE5FAB990336 ON passe');
        $this->addSql('DROP INDEX IDX_D317EE5FA6CC7B2 ON passe');
        $this->addSql('ALTER TABLE passe DROP epreuve_id, DROP eleve_id');
    }
}
