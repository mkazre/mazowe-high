<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTablesMigration extends Migration
{
    public function up()
    {
        // Roles
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 40, 'unique' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('roles', true);

        // Users
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 190, 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role_id'    => ['type' => 'INTEGER', 'null' => true],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('role_id', 'roles', 'id', false, 'SET NULL');
        $this->forge->createTable('users', true);

        // Site settings (logo, contact details, banner, socials) - simple key/value
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'setting_key'   => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'setting_value' => ['type' => 'TEXT', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('site_settings', true);

        // Pages (the 40 public site pages)
        $this->forge->addField([
            'id'               => ['type' => 'INTEGER', 'auto_increment' => true],
            'slug'             => ['type' => 'VARCHAR', 'constraint' => 190, 'unique' => true],
            'nav_group'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'title'            => ['type' => 'VARCHAR', 'constraint' => 190],
            'kicker'           => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'lead'             => ['type' => 'TEXT', 'null' => true],
            'meta_description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'           => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pages', true);

        // Page blocks (ordered content blocks per page, edited from the admin panel)
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'page_id'    => ['type' => 'INTEGER'],
            'type'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'position'   => ['type' => 'INTEGER', 'default' => 0],
            'data'       => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('page_id', 'pages', 'id', false, 'CASCADE');
        $this->forge->createTable('page_blocks', true);

        // Media library
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'filename'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'path'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'alt_text'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'uploaded_by' => ['type' => 'INTEGER', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('media', true);

        // Enquiries: contact form + application form submissions land here
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'type'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'contact'],
            'reference'  => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'subject'    => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'message'    => ['type' => 'TEXT', 'null' => true],
            'payload'    => ['type' => 'TEXT', 'null' => true],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'new'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('enquiries', true);

        // Notices to parents
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 190],
            'body'          => ['type' => 'TEXT'],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'published_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('notices', true);

        // Events calendar
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 190],
            'description' => ['type' => 'TEXT', 'null' => true],
            'location'    => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'starts_at'   => ['type' => 'DATETIME', 'null' => true],
            'ends_at'     => ['type' => 'DATETIME', 'null' => true],
            'tag'         => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('events', true);

        // Blog posts
        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 190],
            'slug'         => ['type' => 'VARCHAR', 'constraint' => 190, 'unique' => true],
            'category'     => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'excerpt'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'body'         => ['type' => 'TEXT', 'null' => true],
            'status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('posts', true);

        // Vacancies (Work at Mazowe Heights)
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 190],
            'department'  => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'closing_date' => ['type' => 'DATE', 'null' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'open'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('vacancies', true);
    }

    public function down()
    {
        $this->forge->dropTable('vacancies', true);
        $this->forge->dropTable('posts', true);
        $this->forge->dropTable('events', true);
        $this->forge->dropTable('notices', true);
        $this->forge->dropTable('enquiries', true);
        $this->forge->dropTable('media', true);
        $this->forge->dropTable('page_blocks', true);
        $this->forge->dropTable('pages', true);
        $this->forge->dropTable('site_settings', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('roles', true);
    }
}
