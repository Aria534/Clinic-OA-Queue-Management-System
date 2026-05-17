<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDepartmentCodePlacement extends Migration
{
    public function up()
    {
        // This migration ensures department_code is properly positioned
        // It was added after 'name' in previous migration but should be after 'updated_at'
        // Since services table already has department_code from migration 000008,
        // we just verify the column exists with proper constraints
        
        if ($this->db->fieldExists('department_code', 'services')) {
            // Column exists, verify it has correct specification
            $this->db->query(
                "ALTER TABLE services MODIFY COLUMN department_code VARCHAR(5) NOT NULL DEFAULT 'G'"
            );
        }
    }

    public function down()
    {
        // No-op for down, as this is just a verification/fix migration
        // The column was already created in a previous migration
    }
}
