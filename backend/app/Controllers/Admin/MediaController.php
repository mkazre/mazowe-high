<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MediaModel;

class MediaController extends BaseController
{
    public function index()
    {
        $model = new MediaModel();
        $items = $model->orderBy('id', 'DESC')->findAll();

        return view('admin/media', [
            'title'  => 'Media library',
            'crumb'  => 'Content',
            'active' => 'media',
            'items'  => $items,
        ]);
    }

    public function upload()
    {
        $file = $this->request->getFile('file');
        if (! $file || ! $file->isValid()) {
            session()->setFlashdata('error', 'Choose an image to upload.');

            return redirect()->to('/admin/media');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $newName);

        (new MediaModel())->insert([
            'filename'    => $file->getClientName(),
            'path'        => '/uploads/' . $newName,
            'alt_text'    => $this->request->getPost('alt_text'),
            'uploaded_by' => session()->get('admin_user_id'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Image uploaded. Copy its path into any page block\'s image field.');

        return redirect()->to('/admin/media');
    }

    public function delete(int $id)
    {
        $model = new MediaModel();
        $item  = $model->find($id);
        if ($item) {
            $path = FCPATH . ltrim($item['path'], '/');
            if (is_file($path)) {
                unlink($path);
            }
            $model->delete($id);
        }

        return redirect()->to('/admin/media');
    }
}
