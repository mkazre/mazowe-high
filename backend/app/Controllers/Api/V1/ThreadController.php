<?php

namespace App\Controllers\Api\V1;

class ThreadController extends ApiBaseController
{
    public function index()
    {
        $ctx = $this->context();
        $threadIds = array_column(
            $this->db->table('thread_participants')->where('user_id', $ctx['user_id'])->get()->getResultArray(),
            'thread_id'
        );
        if (! $threadIds) {
            return $this->response->setJSON(['ok' => true, 'data' => []]);
        }
        $rows = $this->db->table('message_threads')->whereIn('id', $threadIds)->orderBy('created_at', 'DESC')->get()->getResultArray();
        foreach ($rows as &$t) {
            $last = $this->db->table('messages')->where('thread_id', $t['id'])->orderBy('created_at', 'DESC')->get()->getRowArray();
            $t['last_message'] = $last;
        }

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function show(int $id)
    {
        $ctx = $this->context();
        $isParticipant = $this->db->table('thread_participants')->where('thread_id', $id)->where('user_id', $ctx['user_id'])->countAllResults() > 0;
        if (! $isParticipant) {
            return $this->denied();
        }
        $messages = $this->db->table('messages')->where('thread_id', $id)->orderBy('created_at', 'ASC')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $messages]);
    }

    public function store(int $id)
    {
        $ctx = $this->context();
        $isParticipant = $this->db->table('thread_participants')->where('thread_id', $id)->where('user_id', $ctx['user_id'])->countAllResults() > 0;
        if (! $isParticipant) {
            return $this->denied();
        }
        $user = $this->db->table('users')->where('id', $ctx['user_id'])->get()->getRowArray();

        $this->db->table('messages')->insert([
            'thread_id'      => $id,
            'sender_user_id' => $ctx['user_id'],
            'sender_label'   => $user['name'] ?? null,
            'body'           => $this->request->getJsonVar('body'),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true]);
    }
}
