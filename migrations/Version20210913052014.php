<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913052014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference ADD slug VARCHAR(255)');//AGREGANDO tabla
        $this->addSql("UPDATE conference SET slug=CONCAT(LOWER(city),'-',year)");//update lower(minuscula y concatenar los campos para el slug) masivo  *truco para modficar
        $this->addSql('ALTER TABLE conference ALTER COLUMN slug SET NOT NULL');//ALTERANDO la table * para modificar
    }
  
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conference DROP slug');
    }
}
