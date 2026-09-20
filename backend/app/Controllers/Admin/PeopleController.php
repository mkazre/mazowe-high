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

    /** Students don't sign themselves up — there is no public registration. The school
     *  creates the pupil record, then issues a portal login here once, sharing the
     *  one-time password directly with the family (email delivery isn't wired up on
     *  every environment, so this is shown on screen rather than assumed sent). */
    public function createStudentLogin(int $id)
    {
        $student = $this->db->table('students')->where('id', $id)->get()->getRowArray();
        if (! $student) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ($student['user_id']) {
            session()->setFlashdata('error', 'This pupil already has a portal login.');

            return redirect()->to('/admin/people/students');
        }

        $email = trim((string) $this->request->getPost('login_email'));
        if (! $email) {
            session()->setFlashdata('error', 'Enter an email address to create a login for this pupil.');

            return redirect()->to('/admin/people/students');
        }

        [$userId, $isNew, $password] = $this->findOrCreateUser($email, $student['first_name'] . ' ' . $student['last_name'], 'student');
        $this->db->table('students')->where('id', $id)->update(['user_id' => $userId]);

        session()->setFlashdata('success', $isNew
            ? "Login created for {$email}. Temporary password: {$password} — share this with the family directly; it's shown only this once."
            : "Linked to the existing account for {$email} (no password change).");

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

    /** Same model as createStudentLogin — parents don't self-register; the school issues
     *  the login once the guardian record exists and is linked to their child/children. */
    public function createGuardianLogin(int $id)
    {
        $guardian = $this->db->table('guardians')->where('id', $id)->get()->getRowArray();
        if (! $guardian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ($guardian['user_id']) {
            session()->setFlashdata('error', 'This guardian already has a portal login.');

            return redirect()->to('/admin/people/guardians');
        }

        $email = trim((string) ($guardian['email'] ?: $this->request->getPost('login_email')));
        if (! $email) {
            session()->setFlashdata('error', 'This guardian has no email on file — add one before creating a login.');

            return redirect()->to('/admin/people/guardians');
        }

        [$userId, $isNew, $password] = $this->findOrCreateUser($email, $guardian['name'], 'parent');
        $this->db->table('guardians')->where('id', $id)->update(['user_id' => $userId]);

        session()->setFlashdata('success', $isNew
            ? "Login created for {$email}. Temporary password: {$password} — share this with them directly; it's shown only this once."
            : "Linked to the existing account for {$email} (no password change).");

        return redirect()->to('/admin/people/guardians');
    }

    /** Shared by student/guardian/staff login creation: reuse an existing account for this
     *  email if one exists, otherwise create a fresh one with a one-time random password. */
    private function findOrCreateUser(string $email, string $name, string $roleSlug): array
    {
        $existing = $this->db->table('users')->where('email', $email)->get()->getRowArray();
        if ($existing) {
            return [(int) $existing['id'], false, null];
        }

        $password = strtoupper(bin2hex(random_bytes(3))) . '-' . random_int(1000, 9999);
        $role = $this->db->table('roles')->where('slug', $roleSlug)->get()->getRowArray();
        $this->db->table('users')->insert([
            'name' => $name, 'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role_id' => $role['id'] ?? null, 'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return [(int) $this->db->insertID(), true, $password];
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
        $email = (string) $this->request->getPost('email');
        [$userId, $isNew, $password] = $this->findOrCreateUser($email, (string) $this->request->getPost('name'), 'teacher');

        $this->db->table('staff')->insert([
            'user_id' => $userId,
            'staff_number' => $this->request->getPost('staff_number'),
            'department' => $this->request->getPost('department'),
            'position' => $this->request->getPost('position'),
        ]);

        session()->setFlashdata('success', $isNew
            ? "Staff member added. Temporary password for {$email}: {$password} — share this with them directly; it's shown only this once."
            : "Staff member added and linked to the existing account for {$email}.");

        return redirect()->to('/admin/people/staff');
    }

    public function deleteStaff(int $id)
    {
        $this->db->table('staff')->where('id', $id)->delete();

        return redirect()->to('/admin/people/staff');
    }
}
