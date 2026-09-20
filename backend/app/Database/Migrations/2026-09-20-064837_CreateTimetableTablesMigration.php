<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimetableTablesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 40],
            'start_time'  => ['type' => 'VARCHAR', 'constraint' => 10],
            'end_time'    => ['type' => 'VARCHAR', 'constraint' => 10],
            'sort_order'  => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('timetable_periods', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'class_id'   => ['type' => 'INTEGER'],
            'subject_id' => ['type' => 'INTEGER'],
            'staff_id'   => ['type' => 'INTEGER', 'null' => true],
            'period_id'  => ['type' => 'INTEGER'],
            'day_of_week' => ['type' => 'INTEGER'], // 1=Mon .. 5=Fri
            'room'       => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('timetable_entries', true);
    }

    public function down()
    {
        $this->forge->dropTable('timetable_entries', true);
        $this->forge->dropTable('timetable_periods', true);
    }
}
