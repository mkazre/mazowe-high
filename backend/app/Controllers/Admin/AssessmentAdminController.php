<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AssessmentAdminController extends BaseController
{
    public function index()
    {
        $assessments = $this->db->table('assessments a')
            ->select('a.*, c.name as class_name, s.name as subject_name')
            ->join('classes c', 'c.id = a.class_id')
            ->join('subjects s', 's.id = a.subject_id')
            ->orderBy('a.created_at', 'DESC')
            ->get()->getResultArray();

        $reports = $this->db->table('reports r')
            ->select('r.*, s.first_name, s.last_name')
            ->join('students s', 's.id = r.student_id')
            ->orderBy('r.generated_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/assessments/index', [
            'title' => 'Assessment & reports', 'crumb' => 'Daily operations', 'active' => 'assessments',
            'assessments' => $assessments, 'reports' => $reports,
        ]);
    }

    public function show(int $id)
    {
        $assessment = $this->db->table('assessments a')
            ->select('a.*, c.name as class_name, s.name as subject_name')
            ->join('classes c', 'c.id = a.class_id')->join('subjects s', 's.id = a.subject_id')
            ->where('a.id', $id)->get()->getRowArray();

        $students = $this->db->table('students')->where('class_id', $assessment['class_id'])->get()->getResultArray();
        foreach ($students as &$s) {
            $score = $this->db->table('assessment_scores')->where('assessment_id', $id)->where('student_id', $s['id'])->get()->getRowArray();
            $s['score'] = $score['score'] ?? null;
            $s['comment'] = $score['comment'] ?? null;
        }

        return view('admin/assessments/show', ['title' => $assessment['subject_name'] . ' — ' . $assessment['name'], 'crumb' => 'Daily operations', 'active' => 'assessments', 'assessment' => $assessment, 'students' => $students]);
    }

    public function storeScores(int $id)
    {
        $scores = $this->request->getPost('score') ?? [];
        $comments = $this->request->getPost('comment') ?? [];
        foreach ($scores as $studentId => $score) {
            if ($score === '') continue;
            $existing = $this->db->table('assessment_scores')->where('assessment_id', $id)->where('student_id', $studentId)->get()->getRowArray();
            $payload = ['score' => $score, 'comment' => $comments[$studentId] ?? null];
            if ($existing) {
                $this->db->table('assessment_scores')->where('id', $existing['id'])->update($payload);
            } else {
                $this->db->table('assessment_scores')->insert(array_merge($payload, ['assessment_id' => $id, 'student_id' => $studentId]));
            }
        }
        session()->setFlashdata('success', 'Scores saved.');

        return redirect()->to('/admin/assessments/' . $id);
    }

    public function publishReport(int $reportId)
    {
        $this->db->table('reports')->where('id', $reportId)->update(['published' => 1]);

        return redirect()->to('/admin/assessments');
    }
}
