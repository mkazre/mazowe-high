<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransportLibraryCommsTablesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('routes', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'route_id'   => ['type' => 'INTEGER'],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'sort_order' => ['type' => 'INTEGER', 'default' => 0],
            'eta'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'lat'        => ['type' => 'DECIMAL', 'constraint' => '9,6', 'null' => true],
            'lng'        => ['type' => 'DECIMAL', 'constraint' => '9,6', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('stops', true);

        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'route_id'    => ['type' => 'INTEGER'],
            'lat'         => ['type' => 'DECIMAL', 'constraint' => '9,6'],
            'lng'         => ['type' => 'DECIMAL', 'constraint' => '9,6'],
            'recorded_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('vehicle_pings', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'route_id'   => ['type' => 'INTEGER'],
            'student_id' => ['type' => 'INTEGER'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('route_subscriptions', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 190],
            'author'       => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'isbn'         => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'copies_total' => ['type' => 'INTEGER', 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('catalogue_items', true);

        $this->forge->addField([
            'id'                => ['type' => 'INTEGER', 'auto_increment' => true],
            'catalogue_item_id' => ['type' => 'INTEGER'],
            'student_id'        => ['type' => 'INTEGER'],
            'borrowed_at'       => ['type' => 'DATE'],
            'due_at'            => ['type' => 'DATE'],
            'returned_at'       => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('loans', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'subject'    => ['type' => 'VARCHAR', 'constraint' => 190],
            'student_id' => ['type' => 'INTEGER', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('message_threads', true);

        $this->forge->addField([
            'id'        => ['type' => 'INTEGER', 'auto_increment' => true],
            'thread_id' => ['type' => 'INTEGER'],
            'user_id'   => ['type' => 'INTEGER'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('thread_participants', true);

        $this->forge->addField([
            'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
            'thread_id'      => ['type' => 'INTEGER'],
            'sender_user_id' => ['type' => 'INTEGER', 'null' => true],
            'sender_label'   => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'body'           => ['type' => 'TEXT'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('messages', true);
    }

    public function down()
    {
        foreach (['messages', 'thread_participants', 'message_threads', 'loans', 'catalogue_items', 'route_subscriptions', 'vehicle_pings', 'stops', 'routes'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
