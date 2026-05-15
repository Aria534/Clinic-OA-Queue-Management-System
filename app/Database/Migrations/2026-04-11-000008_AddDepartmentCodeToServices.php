<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentCodeToServices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('services', [
            'department_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'default'    => 'G',
                'after'      => 'name',
            ],
        ]);

        // Set default codes for existing services
        $this->db->query("UPDATE services SET department_code = UPPER(LEFT(name, 1)) WHERE department_code IS NULL OR department_code = 'G'");
    }

    public function down()
    {
        $this->forge->dropColumn('services', 'department_code');
    }
}
