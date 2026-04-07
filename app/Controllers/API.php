<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class API extends BaseController
{
    public function queueStatus(int $appointmentId)
    {
        $model       = new AppointmentModel();
        $appointment = $model->getWithDetails($appointmentId);

        if (!$appointment) {
            return $this->response->setJSON([
                'status' => 'not_found',
                'ahead'  => 0,
            ])->setStatusCode(404);
        }

        $db     = \Config\Database::connect();
        $prefix = substr($appointment['queue_number'], 0, strpos($appointment['queue_number'], '-'));

        // Ahead count — same department lang
        $ahead = $db->table('appointments')
            ->where('appointment_date', $appointment['appointment_date'])
            ->like('queue_number', $prefix . '-', 'after')
            ->where('queue_number <', $appointment['queue_number'])
            ->whereIn('status', ['confirmed', 'in_queue', 'pending', 'serving'])
            ->countAllResults();

        $serving = $db->table('appointments')
            ->select('queue_number')
            ->where('appointment_date', $appointment['appointment_date'])
            ->where('status', 'serving')
            ->get()->getRowArray();

        return $this->response->setJSON([
            'status'       => $appointment['status'],
            'ahead'        => $ahead,
            'queue_number' => $appointment['queue_number'],
            'now_serving'  => $serving['queue_number'] ?? null,
            'patient_name' => $appointment['patient_name'],
            'service_name' => $appointment['service_name'],
        ]);
    }

    public function queueLive()
    {
        $db    = \Config\Database::connect();
        $today = date('Y-m-d');

        // Get all services na naa'y activity today
        $services = $db->table('services s')
            ->select('s.id, s.name, s.department_code')
            ->join('appointments a', "a.service_id = s.id AND a.appointment_date = '" . $today . "'", 'inner')
            ->groupBy('s.id')
            ->get()->getResultArray();

        $departments = [];

        foreach ($services as $service) {
            $sid = $service['id'];

            // Currently serving for this service
            $serving = $db->table('appointments a')
                ->select('a.queue_number, COALESCE(a.patient_name, u.name) as patient_name')
                ->join('users u', 'u.id = a.user_id', 'left')
                ->where('a.appointment_date', $today)
                ->where('a.service_id', $sid)
                ->where('a.status', 'serving')
                ->get()->getRowArray();

            // Waiting count
            $waiting = $db->table('appointments')
                ->where('appointment_date', $today)
                ->where('service_id', $sid)
                ->whereIn('status', ['confirmed', 'in_queue', 'pending'])
                ->countAllResults();

            // Completed count
            $completed = $db->table('appointments')
                ->where('appointment_date', $today)
                ->where('service_id', $sid)
                ->where('status', 'completed')
                ->countAllResults();

            // Up next — next 3 waiting
            $upNext = $db->table('appointments a')
                ->select('a.queue_number, COALESCE(a.patient_name, u.name) as patient_name')
                ->join('users u', 'u.id = a.user_id', 'left')
                ->where('a.appointment_date', $today)
                ->where('a.service_id', $sid)
                ->whereIn('a.status', ['confirmed', 'in_queue', 'pending'])
                ->orderBy('a.queue_number', 'ASC')
                ->limit(3)
                ->get()->getResultArray();

            $departments[] = [
                'service_id'      => $sid,
                'service_name'    => $service['name'],
                'department_code' => $service['department_code'],
                'serving'         => $serving ?: null,
                'waiting'         => $waiting,
                'completed'       => $completed,
                'up_next'         => $upNext,
            ];
        }

        return $this->response->setJSON([
            'departments' => $departments,
            'timestamp'   => date('H:i:s'),
        ]);
    }
}