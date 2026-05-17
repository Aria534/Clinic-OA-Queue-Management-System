<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeQueueNumberToVarchar extends Migration
{
    public function up()
    {
        // Queue_number is now created as VARCHAR in CreateAppointmentsTable
        // This migration ensures compatibility if running on existing databases
        if ($this->db->fieldExists('queue_number', 'appointments')) {
            try {
                $this->forge->modifyColumn('appointments', [
                    'queue_number' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 10,
                        'default'    => '',
                    ],
                ]);
            } catch (\Exception $e) {
                // Column might already be VARCHAR, skip if error occurs
                log_message('info', 'Queue_number might already be VARCHAR: ' . $e->getMessage());
            }
        }
    }

    public function down()
    {
        // Revert not recommended as it would lose data
        // This is kept for historical tracking only
    }
}
