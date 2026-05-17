<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQueueTimestampsToAppointments extends Migration
{
    public function up()
    {
        // These fields are now included in the CreateAppointmentsTable migration
        // This migration is kept for historical tracking and backward compatibility
        // No action needed as fields already exist in schema
        if (!$this->db->fieldExists('started_at', 'appointments')) {
            $this->forge->addColumn('appointments', [
                'started_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                    'after'   => 'status',
                ],
            ]);
        }

        if (!$this->db->fieldExists('finished_at', 'appointments')) {
            $this->forge->addColumn('appointments', [
                'finished_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                    'after'   => 'started_at',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('started_at', 'appointments')) {
            $this->forge->dropColumn('appointments', 'started_at');
        }
        if ($this->db->fieldExists('finished_at', 'appointments')) {
            $this->forge->dropColumn('appointments', 'finished_at');
        }
    }
}