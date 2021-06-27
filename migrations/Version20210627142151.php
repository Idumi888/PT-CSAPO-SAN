<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627142151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FA6CC7B2');
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FAB990336');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FAB990336');
        $this->addSql('ALTER TABLE passe DROP FOREIGN KEY FK_D317EE5FA6CC7B2');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id)');
        $this->addSql('ALTER TABLE passe ADD CONSTRAINT FK_D317EE5FA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
    }
}
