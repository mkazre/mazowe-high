<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBoardingCateringTablesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => ['type' => 'INTEGER', 'auto_increment' => true],
            'house_id' => ['type' => 'INTEGER'],
            'name'     => ['type' => 'VARCHAR', 'constraint' => 60],
            'capacity' => ['type' => 'INTEGER', 'default' => 6],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('dormitories', true);

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'dormitory_id'  => ['type' => 'INTEGER'],
            'label'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'student_id'    => ['type' => 'INTEGER', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('beds', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id'   => ['type' => 'INTEGER'],
            'reason'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'depart_at'    => ['type' => 'DATETIME'],
            'return_at'    => ['type' => 'DATETIME'],
            'status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('exeat_requests', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id' => ['type' => 'INTEGER'],
            'reason'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'notes'      => ['type' => 'TEXT', 'null' => true],
            'visited_at' => ['type' => 'DATETIME'],
            'resolved'   => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('sanatorium_visits', true);

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id'    => ['type' => 'INTEGER', 'unique' => true],
            'balance_cents' => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tuck_accounts', true);

        $this->forge->addField([
            'id'              => ['type' => 'INTEGER', 'auto_increment' => true],
            'tuck_account_id' => ['type' => 'INTEGER'],
            'type'            => ['type' => 'VARCHAR', 'constraint' => 20],
            'amount_cents'    => ['type' => 'INTEGER'],
            'note'            => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tuck_transactions', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'day_of_week' => ['type' => 'INTEGER'],
            'day_name'   => ['type' => 'VARCHAR', 'constraint' => 20],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('menu_days', true);

        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'menu_day_id' => ['type' => 'INTEGER'],
            'meal'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'tags'        => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('menu_items', true);

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'menu_item_id'  => ['type' => 'INTEGER'],
            'student_id'    => ['type' => 'INTEGER', 'null' => true],
            'rating'        => ['type' => 'INTEGER'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('meal_ratings', true);
    }

    public function down()
    {
        foreach (['meal_ratings', 'menu_items', 'menu_days', 'tuck_transactions', 'tuck_accounts', 'sanatorium_visits', 'exeat_requests', 'beds', 'dormitories'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
