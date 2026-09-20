<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAcademicAndPeopleTablesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'is_current' => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('academic_years', true);

        $this->forge->addField([
            'id'               => ['type' => 'INTEGER', 'auto_increment' => true],
            'academic_year_id' => ['type' => 'INTEGER'],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 40],
            'starts_on'        => ['type' => 'DATE', 'null' => true],
            'ends_on'          => ['type' => 'DATE', 'null' => true],
            'is_current'       => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('terms', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'sort_order' => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('year_groups', true);

        $this->forge->addField([
            'id'     => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'   => ['type' => 'VARCHAR', 'constraint' => 60],
            'colour' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('houses', true);

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'year_group_id' => ['type' => 'INTEGER'],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 40],
            'house_id'      => ['type' => 'INTEGER', 'null' => true],
            'form_teacher_id' => ['type' => 'INTEGER', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('classes', true);

        $this->forge->addField([
            'id'       => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'     => ['type' => 'VARCHAR', 'constraint' => 80],
            'code'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'pathway'  => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'both'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('subjects', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id'    => ['type' => 'INTEGER', 'null' => true],
            'staff_number' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'department' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'position'   => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('staff', true);

        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'admission_number' => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'first_name'  => ['type' => 'VARCHAR', 'constraint' => 80],
            'last_name'   => ['type' => 'VARCHAR', 'constraint' => 80],
            'dob'         => ['type' => 'DATE', 'null' => true],
            'gender'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'year_group_id' => ['type' => 'INTEGER', 'null' => true],
            'class_id'    => ['type' => 'INTEGER', 'null' => true],
            'house_id'    => ['type' => 'INTEGER', 'null' => true],
            'day_or_boarding' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'day'],
            'user_id'     => ['type' => 'INTEGER', 'null' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('students', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'phone'        => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'user_id'      => ['type' => 'INTEGER', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('guardians', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'guardian_id'  => ['type' => 'INTEGER'],
            'student_id'   => ['type' => 'INTEGER'],
            'relationship' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'is_primary'   => ['type' => 'INTEGER', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('guardian_student', true);
    }

    public function down()
    {
        foreach (['guardian_student', 'guardians', 'students', 'staff', 'subjects', 'classes', 'houses', 'year_groups', 'terms', 'academic_years'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
