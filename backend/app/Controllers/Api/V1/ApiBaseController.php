<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;

abstract class ApiBaseController extends BaseController
{
    /** Resolve the calling user's role + linked student/staff/guardian ids from the JWT-authenticated user id. */
    protected function context(): array
    {
        $userId = $this->request->userId ?? null;
        $user   = $userId ? $this->db->table('users')->where('id', $userId)->get()->getRowArray() : null;
        $role   = $user ? ($this->db->table('roles')->where('id', $user['role_id'])->get()->getRowArray()['slug'] ?? null) : null;

        $studentIds = [];
        $staffId    = null;
        $guardianId = null;

        if ($role === 'student') {
            $s = $this->db->table('students')->where('user_id', $userId)->get()->getRowArray();
            if ($s) $studentIds = [(int) $s['id']];
        } elseif ($role === 'parent') {
            $g = $this->db->table('guardians')->where('user_id', $userId)->get()->getRowArray();
            if ($g) {
                $guardianId = (int) $g['id'];
                $studentIds = array_map('intval', array_column(
                    $this->db->table('guardian_student')->where('guardian_id', $guardianId)->get()->getResultArray(),
                    'student_id'
                ));
            }
        } else {
            $st = $this->db->table('staff')->where('user_id', $userId)->get()->getRowArray();
            if ($st) $staffId = (int) $st['id'];
        }

        return [
            'user_id'     => $userId,
            'role'        => $role,
            'student_ids' => $studentIds,
            'staff_id'    => $staffId,
            'guardian_id' => $guardianId,
        ];
    }

    /** True if the current user is allowed to view this student's records. */
    protected function canAccessStudent(int $studentId, array $ctx): bool
    {
        if (in_array($ctx['role'], ['super_admin', 'head', 'registrar'], true)) {
            return true;
        }
        if (in_array($studentId, $ctx['student_ids'], true)) {
            return true;
        }
        if ($ctx['staff_id']) {
            // Teachers can see students in classes they teach.
            $classIds = array_column(
                $this->db->table('timetable_entries')->where('staff_id', $ctx['staff_id'])->distinct()->select('class_id')->get()->getResultArray(),
                'class_id'
            );
            $student = $this->db->table('students')->where('id', $studentId)->get()->getRowArray();

            return $student && in_array((int) $student['class_id'], $classIds, true);
        }

        return false;
    }

    protected function denied()
    {
        return $this->response->setJSON(['ok' => false, 'error' => 'Forbidden'])->setStatusCode(403);
    }
}
