<?php

namespace App\Controllers;

use App\Models\QueueTicketModel;

class Api extends BaseController
{
    public function display()
    {
        $model = new QueueTicketModel();

        return $this->response->setJSON([
            'success'   => true,
            'timestamp' => date('Y-m-d H:i:s'),
            'serving'   => $model->getCurrentlyServing(),  // null if walay serving
            'queue'     => $model->getTodayQueue(),         // array of waiting tickets
            'stats'     => $model->getTodayStats(),         // waiting/serving/completed/total
        ]);
    }
}