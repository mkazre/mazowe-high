<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TimetableController extends BaseController
{
    public function index()
    {
        $classId = (int) ($this->request->getGet('class_id') ?: 0);
        $classes = $this->db->table('classes')->get()->getResultArray();
        if (! $classId && $classes) {
            $classId = (int) $classes[0]['id'];
        }

        $entries = $classId ? $this->db->table('timetable_entries te')
            ->select('te.*, s.name as subject, tp.name as period, tp.start_time, tp.end_time, u.name as teacher')
            ->join('subjects s', 's.id = te.subject_id')
            ->join('timetable_periods tp', 'tp.id = te.period_id')
            ->join('staff st', 'st.id = te.staff_id', 'left')
            ->join('users u', 'u.id = st.user_id', 'left')
            ->where('te.class_id', $classId)
            ->orderBy('te.day_of_week')->orderBy('tp.sort_order')
            ->get()->getResultArray() : [];

        return view('admin/timetable/index', [
            'title' => 'Timetable', 'crumb' => 'School setup', 'active' => 'timetable',
            'classes' => $classes, 'classId' => $classId, 'entries' => $entries,
            'periods' => $this->db->table('timetable_periods')->orderBy('sort_order')->get()->getResultArray(),
            'subjects' => $this->db->table('subjects')->get()->getResultArray(),
            'staff' => $this->db->table('staff st')->select('st.id, u.name')->join('users u', 'u.id = st.user_id', 'left')->get()->getResultArray(),
        ]);
    }

    public function store()
    {
        $this->db->table('timetable_entries')->insert([
            'class_id'    => (int) $this->request->getPost('class_id'),
            'subject_id'  => (int) $this->request->getPost('subject_id'),
            'staff_id'    => $this->request->getPost('staff_id') ?: null,
            'period_id'   => (int) $this->request->getPost('period_id'),
            'day_of_week' => (int) $this->request->getPost('day_of_week'),
            'room'        => $this->request->getPost('room'),
        ]);

        return redirect()->to('/admin/timetable?class_id=' . (int) $this->request->getPost('class_id'));
    }

    public function delete(int $id)
    {
        $row = $this->db->table('timetable_entries')->where('id', $id)->get()->getRowArray();
        $this->db->table('timetable_entries')->where('id', $id)->delete();

        return redirect()->to('/admin/timetable?class_id=' . (int) ($row['class_id'] ?? 0));
    }
}
