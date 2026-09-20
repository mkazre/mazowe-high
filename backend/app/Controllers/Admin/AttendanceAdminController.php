<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AttendanceAdminController extends BaseController
{
    public function index()
    {
        $classId = (int) ($this->request->getGet('class_id') ?: 0);
        $date    = $this->request->getGet('date') ?: date('Y-m-d');
        $classes = $this->db->table('classes')->get()->getResultArray();
        if (! $classId && $classes) $classId = (int) $classes[0]['id'];

        $students = $classId ? $this->db->table('students')->where('class_id', $classId)->where('status', 'active')->get()->getResultArray() : [];
        foreach ($students as &$s) {
            $mark = $this->db->table('attendance_marks')->where('student_id', $s['id'])->where('mark_date', $date)->get()->getRowArray();
            $s['status'] = $mark['status'] ?? null;
        }

        return view('admin/attendance/index', [
            'title' => 'Attendance', 'crumb' => 'Daily operations', 'active' => 'attendance',
            'classes' => $classes, 'classId' => $classId, 'date' => $date, 'students' => $students,
        ]);
    }

    public function store()
    {
        $classId = (int) $this->request->getPost('class_id');
        $date    = $this->request->getPost('date');
        $marks   = $this->request->getPost('status') ?? []; // [student_id => status]

        foreach ($marks as $studentId => $status) {
            $existing = $this->db->table('attendance_marks')->where('student_id', $studentId)->where('mark_date', $date)->get()->getRowArray();
            if ($existing) {
                $this->db->table('attendance_marks')->where('id', $existing['id'])->update(['status' => $status]);
            } else {
                $this->db->table('attendance_marks')->insert([
                    'student_id' => $studentId, 'class_id' => $classId, 'mark_date' => $date,
                    'status' => $status, 'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        session()->setFlashdata('success', 'Register saved.');

        return redirect()->to('/admin/attendance?class_id=' . $classId . '&date=' . $date);
    }
}
