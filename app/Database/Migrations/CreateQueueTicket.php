<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQueueTickets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'queue_number' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'patient_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['waiting', 'serving', 'completed', 'skipped'],
                'default'    => 'waiting',
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'called_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'completed_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['date', 'status']);
        $this->forge->createTable('queue_tickets');
    }

    public function down()
    {
        $this->forge->dropTable('queue_tickets');
    }
}