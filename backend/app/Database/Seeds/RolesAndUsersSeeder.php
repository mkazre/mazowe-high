<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesAndUsersSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'super_admin', 'head', 'deputy_academic', 'deputy_pastoral', 'bursar',
            'finance_clerk', 'admissions_officer', 'registrar', 'hod', 'teacher',
            'tutor', 'house_parent', 'matron', 'nurse', 'counsellor', 'librarian',
            'transport_manager', 'catering_manager', 'parent', 'student',
        ];

        foreach ($roles as $slug) {
            $exists = $this->db->table('roles')->where('slug', $slug)->get()->getRow();
            if (! $exists) {
                $this->db->table('roles')->insert([
                    'slug'       => $slug,
                    'name'       => ucwords(str_replace('_', ' ', $slug)),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $adminRoleId = $this->db->table('roles')->where('slug', 'super_admin')->get()->getRow()->id;

        $adminEmail = 'admin@mazoweheights.ac.zw';
        $exists = $this->db->table('users')->where('email', $adminEmail)->get()->getRow();
        if (! $exists) {
            $this->db->table('users')->insert([
                'name'       => 'Site Administrator',
                'email'      => $adminEmail,
                'password'   => password_hash('MazoweHeights2027!', PASSWORD_DEFAULT),
                'role_id'    => $adminRoleId,
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
