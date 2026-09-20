<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\EnquiryModel;
use App\Models\EventModel;
use App\Models\NoticeModel;
use App\Models\PageModel;
use App\Models\PostModel;

class ContentController extends BaseController
{
    public function notices()
    {
        $items = (new NoticeModel())->where('status', 'published')->orderBy('published_at', 'DESC')->findAll();

        return $this->response->setJSON(['ok' => true, 'data' => $items]);
    }

    public function events()
    {
        $items = (new EventModel())->where('status', 'published')->orderBy('starts_at', 'ASC')->findAll();

        return $this->response->setJSON(['ok' => true, 'data' => $items]);
    }

    public function posts()
    {
        $items = (new PostModel())->where('status', 'published')->orderBy('published_at', 'DESC')->findAll();

        return $this->response->setJSON(['ok' => true, 'data' => $items]);
    }

    public function page(string $group, string $slug)
    {
        $page = (new PageModel())->withBlocks($group . '/' . $slug);
        if (! $page) {
            return $this->response->setJSON(['ok' => false])->setStatusCode(404);
        }

        return $this->response->setJSON(['ok' => true, 'data' => $page]);
    }

    public function contact()
    {
        $model = new EnquiryModel();
        $model->insert([
            'type'    => 'contact',
            'name'    => $this->request->getJsonVar('name'),
            'email'   => $this->request->getJsonVar('email'),
            'phone'   => $this->request->getJsonVar('phone'),
            'subject' => $this->request->getJsonVar('subject'),
            'message' => $this->request->getJsonVar('message'),
            'status'  => 'new',
        ]);

        return $this->response->setJSON(['ok' => true]);
    }

    public function application()
    {
        $reference = 'MH-2027-' . strtoupper(bin2hex(random_bytes(3)));
        $body = $this->request->getJSON(true) ?? [];

        (new EnquiryModel())->insert([
            'type'      => 'application',
            'reference' => $reference,
            'name'      => ($body['pupil_first_name'] ?? '') . ' ' . ($body['pupil_surname'] ?? ''),
            'email'     => $body['guardian_email'] ?? null,
            'phone'     => $body['guardian_phone'] ?? null,
            'subject'   => 'Online application (mobile)',
            'payload'   => json_encode($body),
            'status'    => 'new',
        ]);

        return $this->response->setJSON(['ok' => true, 'reference' => $reference]);
    }
}
