<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventModel;

class EventController extends BaseController
{
    public function index()
    {
        $items = (new EventModel())->orderBy('starts_at', 'ASC')->findAll();

        return view('admin/simple_list', [
            'title' => 'Events', 'crumb' => 'Communications', 'active' => 'events',
            'headerAction' => ['label' => '+ New event', 'href' => '/admin/events/create'],
            'items' => $items, 'entity' => 'events',
            'columns' => ['title' => 'Title', 'location' => 'Location', 'starts_at' => 'Starts'],
        ]);
    }

    public function create()
    {
        return view('admin/events/form', ['title' => 'New event', 'crumb' => 'Communications', 'active' => 'events', 'item' => null]);
    }

    public function store()
    {
        (new EventModel())->insert($this->fromPost());
        session()->setFlashdata('success', 'Event created.');

        return redirect()->to('/admin/events');
    }

    public function edit(int $id)
    {
        $item = (new EventModel())->find($id);

        return view('admin/events/form', ['title' => 'Edit event', 'crumb' => 'Communications', 'active' => 'events', 'item' => $item]);
    }

    public function update(int $id)
    {
        (new EventModel())->update($id, $this->fromPost());
        session()->setFlashdata('success', 'Event updated.');

        return redirect()->to('/admin/events');
    }

    public function delete(int $id)
    {
        (new EventModel())->delete($id);

        return redirect()->to('/admin/events');
    }

    private function fromPost(): array
    {
        return [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'location'    => $this->request->getPost('location'),
            'starts_at'   => $this->request->getPost('starts_at'),
            'tag'         => $this->request->getPost('tag'),
            'status'      => $this->request->getPost('status') ?: 'published',
        ];
    }
}
