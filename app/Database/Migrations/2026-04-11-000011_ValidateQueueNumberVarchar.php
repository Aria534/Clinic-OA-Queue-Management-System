<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ValidateQueueNumberVarchar extends Migration
{
    public function up()
    {
        // Ensure queue_number is VARCHAR with proper constraints
        if ($this->db->fieldExists('queue_number', 'appointments')) {
            $this->db->query(
                "ALTER TABLE appointments MODIFY COLUMN queue_number VARCHAR(10) NOT NULL DEFAULT ''"
            );
        }
    }

    public function down()
    {
        // Revert to previous state if needed
        if ($this->db->fieldExists('queue_number', 'appointments')) {
            $this->db->query(
                "ALTER TABLE appointments MODIFY COLUMN queue_number VARCHAR(10) DEFAULT ''"
            );
        }
    }
}
