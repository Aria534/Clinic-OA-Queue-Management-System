<?php

namespace App\Controllers;

use App\Models\QueueTicketModel;
use App\Models\ServiceModel;

class Queue extends BaseController
{
    // ----------------------------------------------------------------
    // GET / — Walk-in booking form
    // ----------------------------------------------------------------
    public function index()
    {
        $services = new ServiceModel();
        return view('queue/book', [
            'services' => $services->where('is_active', 1)->findAll(),
        ]);
    }

    // ----------------------------------------------------------------
    // POST /book — Issue queue number
    // ----------------------------------------------------------------
    public function book()
    {
        $rules = [
            'patient_name' => 'required|min_length[2]|max_length[100]',
            'service_id'   => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model  = new QueueTicketModel();
        $number = $model->getNextNumber();

        $id = $model->insert([
            'queue_number' => $number,
            'patient_name' => $this->request->getPost('patient_name'),
            'service_id'   => $this->request->getPost('service_id'),
            'status'       => 'waiting',
            'date'         => date('Y-m-d'),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('ticket/' . $id));
    }

    // ----------------------------------------------------------------
    // GET /ticket/{id} — Show issued ticket
    // ----------------------------------------------------------------
    public function ticket(int $id)
    {
        $model  = new QueueTicketModel();
        $ticket = $model->select('queue_tickets.*, services.name as service_name')
                        ->join('services', 'services.id = queue_tickets.service_id', 'left')
                        ->find($id);

        if (!$ticket) {
            return redirect()->to(base_url('/'))
                ->with('error', 'Ticket not found.');
        }

        return view('queue/ticket', ['ticket' => $ticket]);
    }
}