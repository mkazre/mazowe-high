<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\EnquiryModel;
use App\Models\EventModel;
use App\Models\NoticeModel;
use App\Models\PageModel;
use App\Models\PostModel;
use App\Models\SiteSettingModel;
use App\Models\VacancyModel;

class SiteController extends BaseController
{
    protected PageModel $pages;
    protected SiteSettingModel $settings;

    public function __construct()
    {
        $this->pages    = new PageModel();
        $this->settings = new SiteSettingModel();
    }

    private function siteData(string $activeSlug = ''): array
    {
        return [
            'site' => $this->settings->all(),
            'nav'  => $this->pages->navGroups(),
            'active' => $activeSlug,
        ];
    }

    public function home()
    {
        $page = $this->pages->withBlocks('home');
        if (! $page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('web/page', array_merge($this->siteData('home'), ['page' => $page]));
    }

    public function page(string $group, string $slug)
    {
        $full = $group . '/' . $slug;
        $page = $this->pages->withBlocks($full);
        if (! $page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // notices/news feed & vacancies feed blocks need live data
        foreach ($page['blocks'] as &$block) {
            if ($block['type'] === 'notices_feed') {
                $block['data']['items'] = (new NoticeModel())->where('status', 'published')->orderBy('published_at', 'DESC')->findAll();
            }
            if ($block['type'] === 'vacancies_feed') {
                $block['data']['items'] = (new VacancyModel())->where('status', 'open')->findAll();
            }
        }

        return view('web/page', array_merge($this->siteData($full), ['page' => $page]));
    }

    public function contactSubmit()
    {
        $rules = [
            'name'    => 'required|min_length[2]',
            'message' => 'required|min_length[5]',
        ];
        if (! $this->validate($rules)) {
            return $this->response->setJSON(['ok' => false, 'errors' => $this->validator->getErrors()])->setStatusCode(422);
        }

        // honeypot
        if ($this->request->getPost('website')) {
            return $this->response->setJSON(['ok' => true]);
        }

        $model = new EnquiryModel();
        $model->insert([
            'type'    => 'contact',
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
            'status'  => 'new',
        ]);

        $this->sendMail('contact', $this->request->getPost());

        return $this->response->setJSON(['ok' => true]);
    }

    public function applicationSubmit()
    {
        $rules = [
            'pupil_first_name' => 'required',
            'pupil_surname'    => 'required',
            'guardian_email'   => 'required|valid_email',
        ];
        if (! $this->validate($rules)) {
            return $this->response->setJSON(['ok' => false, 'errors' => $this->validator->getErrors()])->setStatusCode(422);
        }

        $reference = 'MH-2027-' . strtoupper(bin2hex(random_bytes(3)));

        $model = new EnquiryModel();
        $model->insert([
            'type'      => 'application',
            'reference' => $reference,
            'name'      => $this->request->getPost('pupil_first_name') . ' ' . $this->request->getPost('pupil_surname'),
            'email'     => $this->request->getPost('guardian_email'),
            'phone'     => $this->request->getPost('guardian_phone'),
            'subject'   => 'Online application',
            'payload'   => json_encode($this->request->getPost()),
            'status'    => 'new',
        ]);

        $this->sendMail('application', array_merge($this->request->getPost() ?? [], ['reference' => $reference]));

        return $this->response->setJSON(['ok' => true, 'reference' => $reference]);
    }

    private function sendMail(string $type, $data): void
    {
        $settings = $this->settings->all();
        $to       = $settings['admissions_email'] ?? $settings['contact_email'] ?? null;
        if (! $to) {
            return;
        }

        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom($settings['contact_email'] ?? 'no-reply@mazoweheights.ac.zw', $settings['site_name'] ?? 'Website');
        $email->setSubject('New ' . $type . ' — ' . ($settings['site_name'] ?? 'Website'));
        $body = '<pre>' . htmlspecialchars(print_r($data, true)) . '</pre>';
        $email->setMessage($body);
        // best-effort: local dev environments without SMTP just log this
        try {
            $email->send();
        } catch (\Throwable $e) {
            log_message('info', 'Mail not sent (dev env?): ' . $e->getMessage());
        }
    }
}
