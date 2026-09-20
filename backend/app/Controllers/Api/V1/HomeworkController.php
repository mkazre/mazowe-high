<?php

namespace App\Controllers\Api\V1;

class HomeworkController extends ApiBaseController
{
    public function submit(int $id)
    {
        $ctx = $this->context();
        if ($ctx['role'] !== 'student' || ! $ctx['student_ids']) {
            return $this->denied();
        }
        $studentId = $ctx['student_ids'][0];

        $existing = $this->db->table('homework_submissions')->where('homework_id', $id)->where('student_id', $studentId)->get()->getRowArray();
        $data = [
            'status'       => 'submitted',
            'notes'        => $this->request->getJsonVar('notes') ?? $this->request->getPost('notes'),
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->db->table('homework_submissions')->where('id', $existing['id'])->update($data);
        } else {
            $this->db->table('homework_submissions')->insert(array_merge($data, ['homework_id' => $id, 'student_id' => $studentId]));
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
