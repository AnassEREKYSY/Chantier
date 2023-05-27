<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527104743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chantier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_chantier INTEGER DEFAULT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE historique (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, interlocuteur_id INTEGER DEFAULT NULL, chantier_id INTEGER DEFAULT NULL, id_h INTEGER DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, CONSTRAINT FK_EDBFD5EC5DC4D72E FOREIGN KEY (interlocuteur_id) REFERENCES interlocuteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EDBFD5ECD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_EDBFD5EC5DC4D72E ON historique (interlocuteur_id)');
        $this->addSql('CREATE INDEX IDX_EDBFD5ECD0C0049D ON historique (chantier_id)');
        $this->addSql('CREATE TABLE interlocuteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_inter INTEGER DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE interlocuteur_chantier (interlocuteur_id INTEGER NOT NULL, chantier_id INTEGER NOT NULL, PRIMARY KEY(interlocuteur_id, chantier_id), CONSTRAINT FK_9CEA89E75DC4D72E FOREIGN KEY (interlocuteur_id) REFERENCES interlocuteur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9CEA89E7D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9CEA89E75DC4D72E ON interlocuteur_chantier (interlocuteur_id)');
        $this->addSql('CREATE INDEX IDX_9CEA89E7D0C0049D ON interlocuteur_chantier (chantier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chantier');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE interlocuteur');
        $this->addSql('DROP TABLE interlocuteur_chantier');
    }
}
