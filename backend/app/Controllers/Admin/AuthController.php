<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('admin_user_id')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/login');
    }

    public function attemptLogin()
    {
        $rules = ['email' => 'required|valid_email', 'password' => 'required'];
        if (! $this->validate($rules)) {
            session()->setFlashdata('error', 'Enter a valid email and password.');
            return redirect()->to('/admin/login')->withInput();
        }

        $model = new UserModel();
        $user  = $model->verify($this->request->getPost('email'), $this->request->getPost('password'));

        if (! $user) {
            session()->setFlashdata('error', 'Incorrect email or password.');
            return redirect()->to('/admin/login')->withInput();
        }

        $role = $this->db->table('roles')->where('id', $user['role_id'])->get()->getRowArray();

        session()->set([
            'admin_user_id'   => $user['id'],
            'admin_user_name' => $user['name'],
            'admin_user_role' => $role['name'] ?? '',
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->remove(['admin_user_id', 'admin_user_name', 'admin_user_role']);
        return redirect()->to('/admin/login');
    }
}
