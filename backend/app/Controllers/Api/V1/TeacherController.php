<?php

namespace App\Controllers\Api\V1;

class TeacherController extends ApiBaseController
{
    private function requireStaff(): ?int
    {
        $ctx = $this->context();

        return $ctx['staff_id'];
    }

    public function classes()
    {
        $staffId = $this->requireStaff();
        if (! $staffId) return $this->denied();

        $classIds = array_unique(array_column(
            $this->db->table('timetable_entries')->where('staff_id', $staffId)->distinct()->select('class_id')->get()->getResultArray(),
            'class_id'
        ));
        $formTeacherOf = $this->db->table('classes')->where('form_teacher_id', $staffId)->get()->getResultArray();
        $classIds = array_unique(array_merge($classIds, array_column($formTeacherOf, 'id')));

        $rows = $classIds ? $this->db->table('classes')->whereIn('id', $classIds)->get()->getResultArray() : [];
        foreach ($rows as &$c) {
            $c['student_count'] = $this->db->table('students')->where('class_id', $c['id'])->countAllResults();
        }

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function classStudents(int $classId)
    {
        $rows = $this->db->table('students')->where('class_id', $classId)->where('status', 'active')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function classAssessments(int $classId)
    {
        $rows = $this->db->table('assessments a')
            ->select('a.id, a.name, a.max_score, s.name as subject')
            ->join('subjects s', 's.id = a.subject_id')
            ->where('a.class_id', $classId)
            ->orderBy('a.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function assessmentCreate()
    {
        $staffId = $this->requireStaff();
        if (! $staffId) return $this->denied();

        $body = $this->request->getJSON(true) ?? [];
        $this->db->table('assessments')->insert([
            'class_id'   => (int) ($body['class_id'] ?? 0),
            'subject_id' => (int) ($body['subject_id'] ?? 0),
            'name'       => $body['name'] ?? 'Assessment',
            'max_score'  => (int) ($body['max_score'] ?? 100),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true, 'id' => $this->db->insertID()]);
    }

    public function subjects()
    {
        $rows = $this->db->table('subjects')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    /** POST /attendance/bulk { class_id, date, marks: [{student_id, status}] } */
    public function attendanceBulk()
    {
        $staffId = $this->requireStaff();
        if (! $staffId) return $this->denied();

        $body    = $this->request->getJSON(true) ?? [];
        $classId = (int) ($body['class_id'] ?? 0);
        $date    = (string) ($body['date'] ?? date('Y-m-d'));
        $marks   = $body['marks'] ?? [];

        foreach ($marks as $m) {
            $studentId = (int) ($m['student_id'] ?? 0);
            if (! $studentId) continue;
            $existing = $this->db->table('attendance_marks')->where('student_id', $studentId)->where('mark_date', $date)->get()->getRowArray();
            $payload = ['status' => $m['status'] ?? 'present', 'marked_by' => $this->context()['user_id']];
            if ($existing) {
                $this->db->table('attendance_marks')->where('id', $existing['id'])->update($payload);
            } else {
                $this->db->table('attendance_marks')->insert(array_merge($payload, [
                    'student_id' => $studentId, 'class_id' => $classId, 'mark_date' => $date, 'created_at' => date('Y-m-d H:i:s'),
                ]));
            }
        }

        return $this->response->setJSON(['ok' => true]);
    }

    /** POST /assessments/{id}/scores { scores: [{student_id, score, comment}] } */
    public function assessmentScores(int $assessmentId)
    {
        $staffId = $this->requireStaff();
        if (! $staffId) return $this->denied();

        $scores = $this->request->getJSON(true)['scores'] ?? [];
        foreach ($scores as $s) {
            $studentId = (int) ($s['student_id'] ?? 0);
            if (! $studentId) continue;
            $existing = $this->db->table('assessment_scores')->where('assessment_id', $assessmentId)->where('student_id', $studentId)->get()->getRowArray();
            $payload = ['score' => $s['score'] ?? null, 'comment' => $s['comment'] ?? null];
            if ($existing) {
                $this->db->table('assessment_scores')->where('id', $existing['id'])->update($payload);
            } else {
                $this->db->table('assessment_scores')->insert(array_merge($payload, ['assessment_id' => $assessmentId, 'student_id' => $studentId]));
            }
        }

        return $this->response->setJSON(['ok' => true]);
    }

    public function homeworkCreate()
    {
        $staffId = $this->requireStaff();
        if (! $staffId) return $this->denied();

        $this->db->table('homework')->insert([
            'class_id'    => (int) $this->request->getJsonVar('class_id'),
            'subject_id'  => (int) $this->request->getJsonVar('subject_id'),
            'staff_id'    => $staffId,
            'title'       => $this->request->getJsonVar('title'),
            'description' => $this->request->getJsonVar('description'),
            'due_date'    => $this->request->getJsonVar('due_date'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true, 'id' => $this->db->insertID()]);
    }

    public function conductCreate()
    {
        $staffId = $this->requireStaff();
        if (! $staffId) return $this->denied();

        $this->db->table('conduct_marks')->insert([
            'student_id' => (int) $this->request->getJsonVar('student_id'),
            'type'       => $this->request->getJsonVar('type') === 'demerit' ? 'demerit' : 'merit',
            'points'     => (int) ($this->request->getJsonVar('points') ?? 1),
            'reason'     => $this->request->getJsonVar('reason'),
            'awarded_by' => $this->context()['user_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true]);
    }
}
