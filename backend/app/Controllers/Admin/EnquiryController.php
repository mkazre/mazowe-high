<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EnquiryModel;

class EnquiryController extends BaseController
{
    public function index()
    {
        $model = new EnquiryModel();
        $items = $model->orderBy('created_at', 'DESC')->findAll();

        return view('admin/enquiries/index', [
            'title'  => 'Enquiries & applications',
            'crumb'  => 'Admissions',
            'active' => 'enquiries',
            'items'  => $items,
        ]);
    }

    public function show(int $id)
    {
        $model = new EnquiryModel();
        $item  = $model->find($id);
        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $payload = $item['payload'] ? json_decode($item['payload'], true) : null;

        return view('admin/enquiries/show', [
            'title'  => 'Enquiry #' . $id,
            'crumb'  => 'Admissions',
            'active' => 'enquiries',
            'item'   => $item,
            'payload' => $payload,
        ]);
    }

    public function updateStatus(int $id)
    {
        (new EnquiryModel())->update($id, ['status' => $this->request->getPost('status')]);
        session()->setFlashdata('success', 'Status updated.');

        return redirect()->to('/admin/enquiries/' . $id);
    }
}
