<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ThreadAdminController extends BaseController
{
    public function index()
    {
        $threads = $this->db->table('message_threads mt')
            ->select('mt.*, s.first_name, s.last_name')
            ->join('students s', 's.id = mt.student_id', 'left')
            ->orderBy('mt.created_at', 'DESC')
            ->get()->getResultArray();
        foreach ($threads as &$t) {
            $t['messages'] = $this->db->table('messages')->where('thread_id', $t['id'])->orderBy('created_at', 'ASC')->get()->getResultArray();
        }

        return view('admin/threads/index', ['title' => 'Parent messages', 'crumb' => 'Communications', 'active' => 'threads', 'threads' => $threads]);
    }

    public function reply(int $id)
    {
        $this->db->table('messages')->insert([
            'thread_id' => $id, 'sender_user_id' => session()->get('admin_user_id'),
            'sender_label' => session()->get('admin_user_name') . ' (Staff)',
            'body' => $this->request->getPost('body'), 'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/threads');
    }
}
