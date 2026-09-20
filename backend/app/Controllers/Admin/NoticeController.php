<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NoticeModel;

class NoticeController extends BaseController
{
    public function index()
    {
        $items = (new NoticeModel())->orderBy('created_at', 'DESC')->findAll();

        return view('admin/simple_list', [
            'title' => 'Notices', 'crumb' => 'Communications', 'active' => 'notices',
            'headerAction' => ['label' => '+ New notice', 'href' => '/admin/notices/create'],
            'items' => $items, 'entity' => 'notices',
            'columns' => ['title' => 'Title', 'status' => 'Status', 'published_at' => 'Published'],
        ]);
    }

    public function create()
    {
        return view('admin/notices/form', ['title' => 'New notice', 'crumb' => 'Communications', 'active' => 'notices', 'item' => null]);
    }

    public function store()
    {
        (new NoticeModel())->insert([
            'title'        => $this->request->getPost('title'),
            'body'         => $this->request->getPost('body'),
            'status'       => $this->request->getPost('status') ?: 'published',
            'published_at' => date('Y-m-d H:i:s'),
        ]);
        session()->setFlashdata('success', 'Notice published.');

        return redirect()->to('/admin/notices');
    }

    public function edit(int $id)
    {
        $item = (new NoticeModel())->find($id);

        return view('admin/notices/form', ['title' => 'Edit notice', 'crumb' => 'Communications', 'active' => 'notices', 'item' => $item]);
    }

    public function update(int $id)
    {
        (new NoticeModel())->update($id, [
            'title'  => $this->request->getPost('title'),
            'body'   => $this->request->getPost('body'),
            'status' => $this->request->getPost('status'),
        ]);
        session()->setFlashdata('success', 'Notice updated.');

        return redirect()->to('/admin/notices');
    }

    public function delete(int $id)
    {
        (new NoticeModel())->delete($id);

        return redirect()->to('/admin/notices');
    }
}
