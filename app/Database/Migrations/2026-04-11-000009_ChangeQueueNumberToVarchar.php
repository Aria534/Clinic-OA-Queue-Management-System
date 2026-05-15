<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeQueueNumberToVarchar extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('appointments', [
            'queue_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => '0',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('appointments', [
            'queue_number' => [
                'type'    => 'INT',
                'default' => 0,
            ],
        ]);
    }
}
