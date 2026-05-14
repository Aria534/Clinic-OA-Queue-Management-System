<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole();
        }
        return view('User/login');
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $user  = $model->findByEmail($this->request->getPost('email'));

        if (!$user || !$model->verifyPassword($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Only admins can log in — patients just use the walk-in form
        if ($user['role'] !== 'admin') {
            return redirect()->back()->withInput()
                ->with('error', 'Access denied. Please use the queue form instead.');
        }

        session()->set([
            'logged_in' => true,
            'user_id'   => $user['id'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'role'      => $user['role'],
        ]);

        return redirect()->to(base_url('admin'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))
            ->with('success', 'Logged out successfully.');
    }

    private function redirectByRole()
    {
        return redirect()->to(base_url('admin'));
    }
}