<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQueueTicketsTable extends Migration
{
    public function up()
    {
        // Skip if table already exists (from SCHEMA.sql import)
        if ($this->db->tableExists('queue_tickets')) {
            return;
        }

        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'queue_number'  => ['type' => 'INT', 'unsigned' => true],
            'patient_name'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'service_id'    => ['type' => 'INT', 'unsigned' => true],
            'status'        => [
                'type'       => 'ENUM',
                'constraint' => ['waiting', 'serving', 'completed', 'skipped'],
                'default'    => 'waiting',
            ],
            'date'          => ['type' => 'DATE'],
            'called_at'     => ['type' => 'DATETIME', 'null' => true],
            'completed_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_at'    => ['type' => 'DATETIME'],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['date', 'status']);
        $this->forge->createTable('queue_tickets');
    }

    public function down()
    {
        $this->forge->dropTable('queue_tickets', true);
    }
}
