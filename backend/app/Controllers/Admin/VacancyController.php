<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VacancyModel;

class VacancyController extends BaseController
{
    public function index()
    {
        $items = (new VacancyModel())->orderBy('id', 'DESC')->findAll();

        return view('admin/simple_list', [
            'title' => 'Vacancies', 'crumb' => 'Communications', 'active' => 'vacancies',
            'headerAction' => ['label' => '+ New vacancy', 'href' => '/admin/vacancies/create'],
            'items' => $items, 'entity' => 'vacancies',
            'columns' => ['title' => 'Title', 'department' => 'Department', 'status' => 'Status'],
        ]);
    }

    public function create()
    {
        return view('admin/vacancies/form', ['title' => 'New vacancy', 'crumb' => 'Communications', 'active' => 'vacancies', 'item' => null]);
    }

    public function store()
    {
        (new VacancyModel())->insert($this->fromPost());
        session()->setFlashdata('success', 'Vacancy added.');

        return redirect()->to('/admin/vacancies');
    }

    public function edit(int $id)
    {
        $item = (new VacancyModel())->find($id);

        return view('admin/vacancies/form', ['title' => 'Edit vacancy', 'crumb' => 'Communications', 'active' => 'vacancies', 'item' => $item]);
    }

    public function update(int $id)
    {
        (new VacancyModel())->update($id, $this->fromPost());
        session()->setFlashdata('success', 'Vacancy updated.');

        return redirect()->to('/admin/vacancies');
    }

    public function delete(int $id)
    {
        (new VacancyModel())->delete($id);

        return redirect()->to('/admin/vacancies');
    }

    private function fromPost(): array
    {
        return [
            'title'        => $this->request->getPost('title'),
            'department'   => $this->request->getPost('department'),
            'description'  => $this->request->getPost('description'),
            'closing_date' => $this->request->getPost('closing_date') ?: null,
            'status'       => $this->request->getPost('status') ?: 'open',
        ];
    }
}
