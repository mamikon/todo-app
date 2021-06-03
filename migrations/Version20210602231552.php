<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602231552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'tasks table migration';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('tasks');
        $table->addColumn('id', Types::BIGINT, ["unsigned" => true, 'autoincrement' => true,]);
        $table->addColumn('uuid', Types::GUID);
        $table->addColumn('user_uuid', Types::GUID);
        $table->addColumn('title', Types::TEXT);
        $table->addColumn('description', Types::TEXT);
        $table->addColumn('status', Types::SMALLINT, ["unsigned" => true]);
        $table->addColumn('date', Types::DATE_IMMUTABLE);
        $table->addUniqueIndex(['uuid']);
        $table->addIndex(['user_uuid']);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('tasks');

    }
}
