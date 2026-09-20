<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $items = $this->db->table('users')
            ->select('users.*, roles.name as role_name')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->orderBy('users.id', 'ASC')
            ->get()->getResultArray();

        return view('admin/users/index', [
            'title' => 'Users & roles', 'crumb' => 'Administration', 'active' => 'users',
            'items' => $items,
        ]);
    }

    public function create()
    {
        $roles = $this->db->table('roles')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/users/form', ['title' => 'New user', 'crumb' => 'Administration', 'active' => 'users', 'item' => null, 'roles' => $roles]);
    }

    public function store()
    {
        (new UserModel())->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password') ?: bin2hex(random_bytes(4)), PASSWORD_DEFAULT),
            'role_id'  => $this->request->getPost('role_id'),
            'status'   => 'active',
        ]);
        session()->setFlashdata('success', 'User created.');

        return redirect()->to('/admin/users');
    }

    public function edit(int $id)
    {
        $item  = (new UserModel())->find($id);
        $roles = $this->db->table('roles')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/users/form', ['title' => 'Edit user', 'crumb' => 'Administration', 'active' => 'users', 'item' => $item, 'roles' => $roles]);
    }

    public function update(int $id)
    {
        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            'status'  => $this->request->getPost('status') ?: 'active',
        ];
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        (new UserModel())->update($id, $data);
        session()->setFlashdata('success', 'User updated.');

        return redirect()->to('/admin/users');
    }

    public function delete(int $id)
    {
        if ($id === (int) session()->get('admin_user_id')) {
            session()->setFlashdata('error', 'You cannot delete your own account.');

            return redirect()->to('/admin/users');
        }
        (new UserModel())->delete($id);

        return redirect()->to('/admin/users');
    }
}
