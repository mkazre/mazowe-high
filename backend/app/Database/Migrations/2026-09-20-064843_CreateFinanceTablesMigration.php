<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinanceTablesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'year_group_id' => ['type' => 'INTEGER', 'null' => true],
            'term_id'       => ['type' => 'INTEGER', 'null' => true],
            'description'   => ['type' => 'VARCHAR', 'constraint' => 190],
            'amount_cents'  => ['type' => 'INTEGER'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('fee_structures', true);

        $this->forge->addField([
            'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id'     => ['type' => 'INTEGER'],
            'term_id'        => ['type' => 'INTEGER', 'null' => true],
            'invoice_number' => ['type' => 'VARCHAR', 'constraint' => 40, 'unique' => true],
            'total_cents'    => ['type' => 'INTEGER'],
            'paid_cents'     => ['type' => 'INTEGER', 'default' => 0],
            'status'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'unpaid'],
            'due_date'       => ['type' => 'DATE', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('invoices', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'invoice_id'   => ['type' => 'INTEGER'],
            'description'  => ['type' => 'VARCHAR', 'constraint' => 190],
            'amount_cents' => ['type' => 'INTEGER'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('invoice_lines', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'invoice_id'   => ['type' => 'INTEGER'],
            'method'       => ['type' => 'VARCHAR', 'constraint' => 20],
            'amount_cents' => ['type' => 'INTEGER'],
            'reference'    => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'is_test'      => ['type' => 'INTEGER', 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('payments', true);

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'student_id'   => ['type' => 'INTEGER'],
            'description'  => ['type' => 'VARCHAR', 'constraint' => 190],
            'amount_cents' => ['type' => 'INTEGER'],
            'term_id'      => ['type' => 'INTEGER', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('bursaries', true);
    }

    public function down()
    {
        foreach (['bursaries', 'payments', 'invoice_lines', 'invoices', 'fee_structures'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
