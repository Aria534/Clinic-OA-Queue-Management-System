<?php

namespace App\Controllers;

use App\Models\QueueTicketModel;
use App\Models\ServiceModel;

class Admin extends BaseController
{
    // ----------------------------------------------------------------
    // Dashboard
    // ----------------------------------------------------------------
    public function index()
    {
        $model = new QueueTicketModel();
        $stats = $model->getTodayStats();

        return view('admin/dashboard', [
            'page'    => 'dashboard',
            'stats'   => $stats,
            'queue'   => $model->getTodayQueue(),
            'serving' => $model->getCurrentlyServing(),
        ]);
    }

    // ----------------------------------------------------------------
    // Queue Monitor page
    // ----------------------------------------------------------------
    public function queue()
    {
        $model = new QueueTicketModel();
        return view('admin/dashboard', [
            'page'    => 'queue',
            'queue'   => $model->getTodayQueue(),
            'serving' => $model->getCurrentlyServing(),
            'stats'   => $model->getTodayStats(),
        ]);
    }

    // ----------------------------------------------------------------
    // Public Display Screen (TV/Monitor para sa waiting area)
    // ----------------------------------------------------------------
    public function displayScreen()
    {
        return view('queue/display');
    }

    // ----------------------------------------------------------------
    // POST /admin/queue/next — Call next patient
    // ----------------------------------------------------------------
    public function callNext()
    {
        $model = new QueueTicketModel();
        $now   = date('Y-m-d H:i:s');

        // Complete currently serving
        $current = $model->getCurrentlyServing();
        if ($current) {
            $model->update($current['id'], [
                'status'       => 'completed',
                'completed_at' => $now,
            ]);
        }

        // Serve next waiting
        $next = $model->getNextWaiting();
        if (!$next) {
            return redirect()->to('/admin/queue')
                ->with('info', 'No more patients in queue.');
        }

        $model->update($next['id'], [
            'status'    => 'serving',
            'called_at' => $now,
        ]);

        return redirect()->to('/admin/queue')
            ->with('success', 'Now serving #' . str_pad($next['queue_number'], 3, '0', STR_PAD_LEFT)
                . ' — ' . $next['patient_name']);
    }

    // ----------------------------------------------------------------
    // POST /admin/queue/skip — Skip current patient
    // ----------------------------------------------------------------
    public function skipCurrent()
    {
        $model   = new QueueTicketModel();
        $current = $model->getCurrentlyServing();

        if ($current) {
            $model->update($current['id'], ['status' => 'skipped']);
        }

        $next = $model->getNextWaiting();
        if ($next) {
            $model->update($next['id'], [
                'status'    => 'serving',
                'called_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/admin/queue')
                ->with('success', 'Skipped. Now serving #' . str_pad($next['queue_number'], 3, '0', STR_PAD_LEFT));
        }

        return redirect()->to('/admin/queue')
            ->with('info', 'Queue is now empty.');
    }

    // ----------------------------------------------------------------
    // Services
    // ----------------------------------------------------------------
    public function services()
    {
        $model = new ServiceModel();
        return view('admin/dashboard', [
            'page'     => 'services',
            'services' => $model->findAll(),
        ]);
    }

    public function storeService()
    {
        $rules = [
            'name'     => 'required',
            'duration' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $model = new ServiceModel();
        $model->save([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration'    => $this->request->getPost('duration'),
            'is_active'   => 1,
        ]);

        return redirect()->back()->with('success', 'Service added.');
    }

    public function toggleService(int $id)
    {
        $model   = new ServiceModel();
        $service = $model->find($id);
        $model->update($id, ['is_active' => $service['is_active'] ? 0 : 1]);
        return redirect()->back()->with('success', 'Service updated.');
    }
}