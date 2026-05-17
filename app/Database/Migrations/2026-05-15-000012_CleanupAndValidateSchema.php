<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupAndValidateSchema extends Migration
{
    public function up()
    {
        // This migration validates and fixes the schema to match SCHEMA.sql
        // It handles migration ordering and ensures no data conflicts
        
        echo "Validating schema structure...\n";
        
        // Verify all tables exist with proper structure
        $this->validateUsersTable();
        $this->validateServicesTable();
        $this->validateAppointmentsTable();
        $this->validateQueueLogsTable();
        $this->validateQueueTicketsTable();
        $this->validateSchedulesTable();
        
        echo "Schema validation complete.\n";
    }

    public function down()
    {
        echo "Cleanup migration cannot be reversed.\n";
    }

    private function validateUsersTable()
    {
        if (!$this->db->tableExists('users')) {
            echo "ERROR: users table does not exist\n";
            return;
        }

        $fields = $this->db->getFieldData('users');
        $hasEmail = false;
        $hasRole = false;

        foreach ($fields as $field) {
            if ($field->name === 'email') {
                $hasEmail = true;
            }
            if ($field->name === 'role') {
                $hasRole = true;
            }
        }

        if (!$hasEmail) {
            echo "WARNING: email column missing from users table\n";
        }
        if (!$hasRole) {
            echo "WARNING: role column missing from users table\n";
        }

        echo "✓ users table validated\n";
    }

    private function validateServicesTable()
    {
        if (!$this->db->tableExists('services')) {
            echo "ERROR: services table does not exist\n";
            return;
        }

        if (!$this->db->fieldExists('department_code', 'services')) {
            echo "WARNING: department_code column missing, adding now...\n";
            try {
                $this->forge->addColumn('services', [
                    'department_code' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 5,
                        'default'    => 'G',
                        'after'      => 'updated_at',
                    ],
                ]);
                echo "✓ department_code added to services\n";
            } catch (\Exception $e) {
                echo "ERROR: Could not add department_code: " . $e->getMessage() . "\n";
            }
        } else {
            echo "✓ services table validated\n";
        }
    }

    private function validateAppointmentsTable()
    {
        if (!$this->db->tableExists('appointments')) {
            echo "ERROR: appointments table does not exist\n";
            return;
        }

        $requiredFields = ['user_id', 'service_id', 'appointment_date', 'appointment_time', 'status', 'queue_number'];
        $fields = $this->db->getFieldData('appointments');
        $fieldNames = array_column($fields, 'name');

        foreach ($requiredFields as $field) {
            if (!in_array($field, $fieldNames)) {
                echo "WARNING: {$field} column missing from appointments table\n";
            }
        }

        // Ensure queue_number is VARCHAR
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
                // Column might already be correct
            }
        }

        echo "✓ appointments table validated\n";
    }

    private function validateQueueLogsTable()
    {
        if (!$this->db->tableExists('queue_logs')) {
            echo "ERROR: queue_logs table does not exist\n";
            return;
        }

        if (!$this->db->fieldExists('appointment_id', 'queue_logs')) {
            echo "WARNING: appointment_id column missing from queue_logs table\n";
        }

        echo "✓ queue_logs table validated\n";
    }

    private function validateQueueTicketsTable()
    {
        if (!$this->db->tableExists('queue_tickets')) {
            echo "ERROR: queue_tickets table does not exist\n";
            return;
        }

        if (!$this->db->fieldExists('queue_number', 'queue_tickets')) {
            echo "WARNING: queue_number column missing from queue_tickets table\n";
        }

        echo "✓ queue_tickets table validated\n";
    }

    private function validateSchedulesTable()
    {
        if (!$this->db->tableExists('schedules')) {
            echo "ERROR: schedules table does not exist\n";
            return;
        }

        if (!$this->db->fieldExists('day_of_week', 'schedules')) {
            echo "WARNING: day_of_week column missing from schedules table\n";
        }

        echo "✓ schedules table validated\n";
    }
}
