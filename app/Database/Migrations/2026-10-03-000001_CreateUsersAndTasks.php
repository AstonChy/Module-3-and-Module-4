<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersAndTasks extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('users')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'username' => ['type' => 'VARCHAR', 'constraint' => 100],
                'full_name' => ['type' => 'VARCHAR', 'constraint' => 150],
                'role' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Staff'],
                'password' => ['type' => 'VARCHAR', 'constraint' => 255],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('username');
            $this->forge->createTable('users');
        } elseif (! $this->db->fieldExists('password', 'users')) {
            $this->forge->addColumn('users', ['password' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '']]);
        }

        if (! $this->db->tableExists('tasks')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'title' => ['type' => 'VARCHAR', 'constraint' => 150],
                'description' => ['type' => 'TEXT', 'null' => true],
                'task_date' => ['type' => 'DATE'],
                'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('tasks');
        } elseif (! $this->db->fieldExists('is_archived', 'tasks')) {
            $this->forge->addColumn('tasks', ['is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]]);
        }

        $users = $this->db->table('users');
        $demoUser = $users->where('username', 'admin01')->get()->getRowArray();
        if (! $demoUser) {
            $users->insert([
                'username' => 'admin01',
                'full_name' => 'Alex Admin',
                'role' => 'Administrator',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } elseif (empty($demoUser['password'])) {
            $users->where('id', $demoUser['id'])->update([
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($this->db->tableExists('tasks') && ! $this->db->table('tasks')->countAllResults()) {
            $this->db->table('tasks')->insertBatch([
                ['title' => 'Review daily sales report', 'description' => 'Check yesterday\'s sales totals and reconcile the cash drawer.', 'task_date' => date('Y-m-d'), 'is_archived' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['title' => 'Update product inventory', 'description' => 'Record the latest stock count for fast-moving products.', 'task_date' => date('Y-m-d', strtotime('+1 day')), 'is_archived' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['title' => 'Prepare weekly summary', 'description' => 'Prepare a short summary for the store manager.', 'task_date' => date('Y-m-d', strtotime('+2 days')), 'is_archived' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('tasks', true);
        $this->forge->dropTable('users', true);
    }
}
