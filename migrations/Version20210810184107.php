<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210810184107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the \'code\' table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE code
            (
                id         INT AUTO_INCREMENT NOT NULL,
                created_at DATETIME           NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                value      VARCHAR(10)        NOT NULL,
                INDEX code_index (value),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
              COLLATE `utf8mb4_unicode_ci`
              ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE code');
    }
}
