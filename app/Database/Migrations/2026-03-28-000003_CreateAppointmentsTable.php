<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        // Skip if table already exists (from SCHEMA.sql import)
        if ($this->db->tableExists('appointments')) {
            return;
        }

        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'            => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'patient_name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'patient_email'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'service_id'         => ['type' => 'INT', 'unsigned' => true],
            'appointment_date'   => ['type' => 'DATE'],
            'appointment_time'   => ['type' => 'TIME'],
            'queue_number'       => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => ''],
            'status'             => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'in_queue', 'serving', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'started_at'         => ['type' => 'DATETIME', 'null' => true],
            'finished_at'        => ['type' => 'DATETIME', 'null' => true],
            'notes'              => ['type' => 'TEXT', 'null' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('service_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('appointments');
    }

    public function down()
    {
        $this->forge->dropTable('appointments', true);
    }
}
