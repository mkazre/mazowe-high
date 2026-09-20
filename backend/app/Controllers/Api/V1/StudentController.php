<?php

namespace App\Controllers\Api\V1;

class StudentController extends ApiBaseController
{
    private function guard(int $id)
    {
        $ctx = $this->context();
        if (! $this->canAccessStudent($id, $ctx)) {
            return $this->denied();
        }

        return null;
    }

    public function timetable(int $id)
    {
        if ($r = $this->guard($id)) return $r;

        $student = $this->db->table('students')->where('id', $id)->get()->getRowArray();
        if (! $student) return $this->response->setJSON(['ok' => false])->setStatusCode(404);

        $rows = $this->db->table('timetable_entries te')
            ->select('te.day_of_week, te.room, s.name as subject, tp.name as period, tp.start_time, tp.end_time, u.name as teacher')
            ->join('subjects s', 's.id = te.subject_id')
            ->join('timetable_periods tp', 'tp.id = te.period_id')
            ->join('staff st', 'st.id = te.staff_id', 'left')
            ->join('users u', 'u.id = st.user_id', 'left')
            ->where('te.class_id', $student['class_id'])
            ->orderBy('te.day_of_week', 'ASC')->orderBy('tp.sort_order', 'ASC')
            ->get()->getResultArray();

        $byDay = [1 => [], 2 => [], 3 => [], 4 => [], 5 => []];
        foreach ($rows as $r) {
            $byDay[(int) $r['day_of_week']][] = $r;
        }

        return $this->response->setJSON(['ok' => true, 'data' => $byDay]);
    }

    public function attendance(int $id)
    {
        if ($r = $this->guard($id)) return $r;

        $rows = $this->db->table('attendance_marks')->where('student_id', $id)->orderBy('mark_date', 'DESC')->limit(60)->get()->getResultArray();
        $present = count(array_filter($rows, fn ($r) => $r['status'] === 'present'));
        $summary = ['present' => $present, 'total' => count($rows), 'rate' => count($rows) ? round($present / count($rows) * 100) : 100];

        return $this->response->setJSON(['ok' => true, 'summary' => $summary, 'data' => $rows]);
    }

    public function grades(int $id)
    {
        if ($r = $this->guard($id)) return $r;

        $rows = $this->db->table('assessment_scores asc_')
            ->select('asc_.score, asc_.comment, a.name as assessment, a.max_score, sub.name as subject')
            ->join('assessments a', 'a.id = asc_.assessment_id')
            ->join('subjects sub', 'sub.id = a.subject_id')
            ->where('asc_.student_id', $id)
            ->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function reports(int $id)
    {
        if ($r = $this->guard($id)) return $r;

        $reports = $this->db->table('reports')->where('student_id', $id)->where('published', 1)->orderBy('generated_at', 'DESC')->get()->getResultArray();
        foreach ($reports as &$rep) {
            $rep['comments'] = $this->db->table('report_comments rc')
                ->select('rc.grade, rc.effort, rc.comment, s.name as subject')
                ->join('subjects s', 's.id = rc.subject_id')
                ->where('rc.report_id', $rep['id'])
                ->get()->getResultArray();
        }

        return $this->response->setJSON(['ok' => true, 'data' => $reports]);
    }

    public function conduct(int $id)
    {
        if ($r = $this->guard($id)) return $r;

        $rows = $this->db->table('conduct_marks')->where('student_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $merits = array_sum(array_map(fn ($r) => $r['type'] === 'merit' ? (int) $r['points'] : 0, $rows));
        $demerits = array_sum(array_map(fn ($r) => $r['type'] === 'demerit' ? (int) $r['points'] : 0, $rows));

        return $this->response->setJSON(['ok' => true, 'summary' => ['merits' => $merits, 'demerits' => $demerits], 'data' => $rows]);
    }

    public function homework(int $id)
    {
        if ($r = $this->guard($id)) return $r;

        $student = $this->db->table('students')->where('id', $id)->get()->getRowArray();

        $rows = $this->db->table('homework h')
            ->select('h.id, h.title, h.description, h.due_date, s.name as subject, hs.status, hs.submitted_at, hs.notes, hs.id as submission_id')
            ->join('subjects s', 's.id = h.subject_id')
            ->join('homework_submissions hs', 'hs.homework_id = h.id AND hs.student_id = ' . (int) $id, 'left')
            ->where('h.class_id', $student['class_id'])
            ->orderBy('h.due_date', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }
}
