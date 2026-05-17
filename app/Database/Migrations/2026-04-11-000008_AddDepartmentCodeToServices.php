<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentCodeToServices extends Migration
{
    public function up()
    {
        // Department_code is now included in CreateServicesTable migration
        // This migration adds it only if it doesn't already exist
        if (!$this->db->fieldExists('department_code', 'services')) {
            $this->forge->addColumn('services', [
                'department_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 5,
                    'default'    => 'G',
                    'after'      => 'updated_at',
                ],
            ]);
        }

        // Ensure all existing services have proper department codes
        $this->db->query(
            "UPDATE services SET department_code = 'G' WHERE department_code IS NULL OR department_code = ''"
        );
    }

    public function down()
    {
        if ($this->db->fieldExists('department_code', 'services')) {
            $this->forge->dropColumn('services', 'department_code');
        }
    }
}
