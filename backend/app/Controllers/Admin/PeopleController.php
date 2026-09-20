<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class PeopleController extends BaseController
{
    public function students()
    {
        $rows = $this->db->table('students s')
            ->select('s.*, c.name as class_name, h.name as house_name')
            ->join('classes c', 'c.id = s.class_id', 'left')
            ->join('houses h', 'h.id = s.house_id', 'left')
            ->orderBy('s.last_name', 'ASC')
            ->get()->getResultArray();

        return view('admin/people/students', [
            'title' => 'Students', 'crumb' => 'School setup', 'active' => 'people',
            'rows' => $rows,
            'classes' => $this->db->table('classes')->get()->getResultArray(),
            'houses' => $this->db->table('houses')->get()->getResultArray(),
            'yearGroups' => $this->db->table('year_groups')->orderBy('sort_order')->get()->getResultArray(),
        ]);
    }

    public function storeStudent()
    {
        $this->db->table('students')->insert([
            'admission_number' => $this->request->getPost('admission_number') ?: ('MH-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)))),
            'first_name'       => $this->request->getPost('first_name'),
            'last_name'        => $this->request->getPost('last_name'),
            'dob'              => $this->request->getPost('dob') ?: null,
            'gender'           => $this->request->getPost('gender'),
            'year_group_id'    => $this->request->getPost('year_group_id') ?: null,
            'class_id'         => $this->request->getPost('class_id') ?: null,
            'house_id'         => $this->request->getPost('house_id') ?: null,
            'day_or_boarding'  => $this->request->getPost('day_or_boarding') ?: 'day',
            'status'           => 'active',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);
        session()->setFlashdata('success', 'Student added.');

        return redirect()->to('/admin/people/students');
    }

    public function deleteStudent(int $id)
    {
        $this->db->table('students')->where('id', $id)->delete();

        return redirect()->to('/admin/people/students');
    }

    public function guardians()
    {
        $rows = $this->db->table('guardians')->get()->getResultArray();
        foreach ($rows as &$g) {
            $g['children'] = $this->db->table('guardian_student gs')
                ->select('s.first_name, s.last_name, gs.relationship')
                ->join('students s', 's.id = gs.student_id')
                ->where('gs.guardian_id', $g['id'])
                ->get()->getResultArray();
        }
        $students = $this->db->table('students')->get()->getResultArray();

        return view('admin/people/guardians', [
            'title' => 'Guardians', 'crumb' => 'School setup', 'active' => 'people', 'rows' => $rows, 'students' => $students,
        ]);
    }

    public function storeGuardian()
    {
        $this->db->table('guardians')->insert([
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ]);
        $guardianId = (int) $this->db->insertID();

        $studentId = (int) $this->request->getPost('student_id');
        if ($studentId) {
            $this->db->table('guardian_student')->insert([
                'guardian_id' => $guardianId, 'student_id' => $studentId,
                'relationship' => $this->request->getPost('relationship'), 'is_primary' => 1,
            ]);
        }
        session()->setFlashdata('success', 'Guardian added.');

        return redirect()->to('/admin/people/guardians');
    }

    public function deleteGuardian(int $id)
    {
        $this->db->table('guardian_student')->where('guardian_id', $id)->delete();
        $this->db->table('guardians')->where('id', $id)->delete();

        return redirect()->to('/admin/people/guardians');
    }

    public function staff()
    {
        $rows = $this->db->table('staff st')
            ->select('st.*, u.name, u.email')
            ->join('users u', 'u.id = st.user_id', 'left')
            ->get()->getResultArray();

        return view('admin/people/staff', ['title' => 'Staff', 'crumb' => 'School setup', 'active' => 'people', 'rows' => $rows]);
    }

    public function storeStaff()
    {
        $email = $this->request->getPost('email');
        $existing = $this->db->table('users')->where('email', $email)->get()->getRowArray();
        if ($existing) {
            $userId = (int) $existing['id'];
        } else {
            $teacherRole = $this->db->table('roles')->where('slug', 'teacher')->get()->getRowArray();
            $this->db->table('users')->insert([
                'name' => $this->request->getPost('name'), 'email' => $email,
                'password' => password_hash(bin2hex(random_bytes(6)), PASSWORD_DEFAULT),
                'role_id' => $teacherRole['id'] ?? null, 'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $userId = (int) $this->db->insertID();
        }

        $this->db->table('staff')->insert([
            'user_id' => $userId,
            'staff_number' => $this->request->getPost('staff_number'),
            'department' => $this->request->getPost('department'),
            'position' => $this->request->getPost('position'),
        ]);
        session()->setFlashdata('success', 'Staff member added. A user account was created if one did not already exist for this email.');

        return redirect()->to('/admin/people/staff');
    }

    public function deleteStaff(int $id)
    {
        $this->db->table('staff')->where('id', $id)->delete();

        return redirect()->to('/admin/people/staff');
    }
}
