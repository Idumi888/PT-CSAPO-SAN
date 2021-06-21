<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607070351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE epreuve_utilisateur');
        $this->addSql('ALTER TABLE epreuve ADD utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE epreuve ADD CONSTRAINT FK_D6ADE47F1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_D6ADE47F1E969C5 ON epreuve (utilisateurs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE epreuve_utilisateur (epreuve_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_B928A63FAB990336 (epreuve_id), INDEX IDX_B928A63FFB88E14F (utilisateur_id), PRIMARY KEY(epreuve_id, utilisateur_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE epreuve_utilisateur ADD CONSTRAINT FK_B928A63FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epreuve_utilisateur ADD CONSTRAINT FK_B928A63FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epreuve DROP FOREIGN KEY FK_D6ADE47F1E969C5');
        $this->addSql('DROP INDEX IDX_D6ADE47F1E969C5 ON epreuve');
        $this->addSql('ALTER TABLE epreuve DROP utilisateurs_id');
    }
}
