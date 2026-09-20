<?php

namespace App\Controllers\Api\V1;

use App\Libraries\JwtService;
use App\Models\UserModel;

class AuthController extends ApiBaseController
{
    public function login()
    {
        $email    = $this->request->getJsonVar('email') ?? $this->request->getPost('email');
        $password = $this->request->getJsonVar('password') ?? $this->request->getPost('password');

        $user = (new UserModel())->verify((string) $email, (string) $password);
        if (! $user) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Invalid credentials'])->setStatusCode(401);
        }

        $jwt = new JwtService();
        $role = $this->db->table('roles')->where('id', $user['role_id'])->get()->getRowArray();

        return $this->response->setJSON([
            'ok'            => true,
            'access_token'  => $jwt->issueAccessToken((int) $user['id'], ['role' => $role['slug'] ?? null]),
            'refresh_token' => $jwt->issueRefreshToken((int) $user['id']),
            'user'          => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $role['slug'] ?? null,
            ],
        ]);
    }

    public function refresh()
    {
        $token = $this->request->getJsonVar('refresh_token') ?? $this->request->getPost('refresh_token');
        $jwt   = new JwtService();
        $payload = $jwt->validate((string) $token);

        if (! $payload || ($payload['typ'] ?? '') !== 'refresh') {
            return $this->response->setJSON(['ok' => false, 'error' => 'Invalid refresh token'])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'ok'           => true,
            'access_token' => $jwt->issueAccessToken((int) $payload['sub']),
        ]);
    }

    public function me()
    {
        $userId = $this->request->userId ?? null;
        $user   = $userId ? (new UserModel())->find($userId) : null;

        if (! $user) {
            return $this->response->setJSON(['ok' => false])->setStatusCode(404);
        }

        $role = $this->db->table('roles')->where('id', $user['role_id'])->get()->getRowArray();
        $ctx  = $this->context();

        $children = [];
        if ($ctx['role'] === 'parent' && $ctx['student_ids']) {
            $children = $this->db->table('students')->whereIn('id', $ctx['student_ids'])->get()->getResultArray();
        }

        return $this->response->setJSON([
            'ok'   => true,
            'user' => [
                'id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $role['slug'] ?? null,
                'student_id' => $ctx['student_ids'][0] ?? null,
                'staff_id'   => $ctx['staff_id'],
                'children'   => $children,
            ],
        ]);
    }
}
