<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class SettingsController extends BaseController
{
    public function index()
    {
        $model = new SiteSettingModel();

        return view('admin/settings', [
            'title'  => 'Site settings',
            'crumb'  => 'Content',
            'active' => 'settings',
            'settings' => $model->all(),
        ]);
    }

    public function update()
    {
        $model = new SiteSettingModel();

        $fields = [
            'site_name', 'site_strapline', 'banner_text', 'address_line', 'phone',
            'admissions_email', 'contact_email', 'facebook_url', 'instagram_url',
            'twitter_url', 'footer_note', 'motto', 'show_assistant',
        ];

        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null) {
                $model->setValue($field, $value);
            }
        }
        $model->setValue('show_assistant', $this->request->getPost('show_assistant') ? '1' : '0');

        // Logo uploads
        foreach (['logo_header' => 'logo_header_file', 'logo_footer' => 'logo_footer_file'] as $settingKey => $fieldName) {
            $file = $this->request->getFile($fieldName);
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $newName = $settingKey . '-' . time() . '.' . $file->getExtension();
                $file->move(FCPATH . 'uploads', $newName);
                $model->setValue($settingKey, '/uploads/' . $newName);
            }
        }

        session()->setFlashdata('success', 'Site settings updated.');

        return redirect()->to('/admin/settings');
    }
}
