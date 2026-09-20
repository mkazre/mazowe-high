<?php

namespace App\Controllers\Api\V1;

class ExeatController extends ApiBaseController
{
    public function index()
    {
        $ctx = $this->context();
        if (! $ctx['student_ids']) {
            return $this->response->setJSON(['ok' => true, 'data' => []]);
        }
        $rows = $this->db->table('exeat_requests')->whereIn('student_id', $ctx['student_ids'])->orderBy('created_at', 'DESC')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function store()
    {
        $ctx = $this->context();
        if (! $ctx['student_ids']) {
            return $this->denied();
        }

        $this->db->table('exeat_requests')->insert([
            'student_id' => $ctx['student_ids'][0],
            'reason'     => $this->request->getJsonVar('reason'),
            'depart_at'  => $this->request->getJsonVar('depart_at'),
            'return_at'  => $this->request->getJsonVar('return_at'),
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true, 'id' => $this->db->insertID()]);
    }
}
