<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttendanceConductAssessmentTablesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id' => ['type' => 'INTEGER'],
            'class_id'   => ['type' => 'INTEGER', 'null' => true],
            'period_id'  => ['type' => 'INTEGER', 'null' => true],
            'mark_date'  => ['type' => 'DATE'],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'present'],
            'marked_by'  => ['type' => 'INTEGER', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('attendance_marks', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id' => ['type' => 'INTEGER'],
            'type'       => ['type' => 'VARCHAR', 'constraint' => 10], // merit | demerit
            'points'     => ['type' => 'INTEGER', 'default' => 1],
            'reason'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'awarded_by' => ['type' => 'INTEGER', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('conduct_marks', true);

        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'class_id'    => ['type' => 'INTEGER'],
            'subject_id'  => ['type' => 'INTEGER'],
            'staff_id'    => ['type' => 'INTEGER', 'null' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 190],
            'description' => ['type' => 'TEXT', 'null' => true],
            'due_date'    => ['type' => 'DATE', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('homework', true);

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'homework_id'   => ['type' => 'INTEGER'],
            'student_id'    => ['type' => 'INTEGER'],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'submitted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('homework_submissions', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'class_id'   => ['type' => 'INTEGER'],
            'subject_id' => ['type' => 'INTEGER'],
            'term_id'    => ['type' => 'INTEGER', 'null' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'max_score'  => ['type' => 'INTEGER', 'default' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('assessments', true);

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'assessment_id' => ['type' => 'INTEGER'],
            'student_id'    => ['type' => 'INTEGER'],
            'score'         => ['type' => 'DECIMAL', 'constraint' => '6,2', 'null' => true],
            'comment'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('assessment_scores', true);

        $this->forge->addField([
            'id'               => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id'       => ['type' => 'INTEGER'],
            'term_id'          => ['type' => 'INTEGER', 'null' => true],
            'overall_comment'  => ['type' => 'TEXT', 'null' => true],
            'published'        => ['type' => 'INTEGER', 'default' => 0],
            'generated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('reports', true);

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'report_id'  => ['type' => 'INTEGER'],
            'subject_id' => ['type' => 'INTEGER'],
            'grade'      => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'effort'     => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'comment'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('report_comments', true);
    }

    public function down()
    {
        foreach (['report_comments', 'reports', 'assessment_scores', 'assessments', 'homework_submissions', 'homework', 'conduct_marks', 'attendance_marks'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
