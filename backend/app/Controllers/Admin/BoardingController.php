<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BoardingController extends BaseController
{
    public function dormitories()
    {
        $dorms = $this->db->table('dormitories d')->select('d.*, h.name as house_name')->join('houses h', 'h.id = d.house_id', 'left')->get()->getResultArray();
        foreach ($dorms as &$d) {
            $d['beds'] = $this->db->table('beds b')
                ->select('b.*, s.first_name, s.last_name')
                ->join('students s', 's.id = b.student_id', 'left')
                ->where('b.dormitory_id', $d['id'])
                ->get()->getResultArray();
        }

        return view('admin/boarding/dormitories', [
            'title' => 'Bed allocation', 'crumb' => 'Boarding & catering', 'active' => 'boarding',
            'dorms' => $dorms, 'houses' => $this->db->table('houses')->get()->getResultArray(),
            'students' => $this->db->table('students')->where('day_or_boarding', 'boarding')->get()->getResultArray(),
        ]);
    }

    public function storeDormitory()
    {
        $this->db->table('dormitories')->insert([
            'house_id' => (int) $this->request->getPost('house_id'),
            'name'     => $this->request->getPost('name'),
            'capacity' => (int) $this->request->getPost('capacity') ?: 6,
        ]);
        $dormId = (int) $this->db->insertID();
        for ($i = 1; $i <= (int) $this->request->getPost('capacity') ?: 6; $i++) {
            $this->db->table('beds')->insert(['dormitory_id' => $dormId, 'label' => 'Bed ' . $i]);
        }

        return redirect()->to('/admin/boarding/dormitories');
    }

    public function assignBed(int $bedId)
    {
        $this->db->table('beds')->where('id', $bedId)->update(['student_id' => $this->request->getPost('student_id') ?: null]);

        return redirect()->to('/admin/boarding/dormitories');
    }

    public function exeats()
    {
        $rows = $this->db->table('exeat_requests er')
            ->select('er.*, s.first_name, s.last_name')
            ->join('students s', 's.id = er.student_id')
            ->orderBy('er.created_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/boarding/exeats', ['title' => 'Exeat requests', 'crumb' => 'Boarding & catering', 'active' => 'boarding', 'rows' => $rows]);
    }

    public function updateExeatStatus(int $id)
    {
        $this->db->table('exeat_requests')->where('id', $id)->update(['status' => $this->request->getPost('status')]);

        return redirect()->to('/admin/boarding/exeats');
    }

    public function sanatorium()
    {
        $rows = $this->db->table('sanatorium_visits sv')
            ->select('sv.*, s.first_name, s.last_name')
            ->join('students s', 's.id = sv.student_id')
            ->orderBy('sv.visited_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/boarding/sanatorium', [
            'title' => 'Sanatorium', 'crumb' => 'Boarding & catering', 'active' => 'boarding', 'rows' => $rows,
            'students' => $this->db->table('students')->get()->getResultArray(),
        ]);
    }

    public function storeVisit()
    {
        $this->db->table('sanatorium_visits')->insert([
            'student_id' => (int) $this->request->getPost('student_id'),
            'reason'     => $this->request->getPost('reason'),
            'notes'      => $this->request->getPost('notes'),
            'visited_at' => date('Y-m-d H:i:s'),
            'resolved'   => 0,
        ]);

        return redirect()->to('/admin/boarding/sanatorium');
    }

    public function resolveVisit(int $id)
    {
        $this->db->table('sanatorium_visits')->where('id', $id)->update(['resolved' => 1]);

        return redirect()->to('/admin/boarding/sanatorium');
    }

    public function tuck()
    {
        $rows = $this->db->table('tuck_accounts ta')
            ->select('ta.*, s.first_name, s.last_name')
            ->join('students s', 's.id = ta.student_id')
            ->get()->getResultArray();

        return view('admin/boarding/tuck', ['title' => 'Tuck accounts', 'crumb' => 'Boarding & catering', 'active' => 'boarding', 'rows' => $rows]);
    }

    public function tuckTransaction(int $accountId)
    {
        $type = $this->request->getPost('type');
        $amount = (int) round(((float) $this->request->getPost('amount')) * 100);
        $signed = $type === 'purchase' ? -$amount : $amount;

        $this->db->table('tuck_transactions')->insert([
            'tuck_account_id' => $accountId, 'type' => $type, 'amount_cents' => $signed,
            'note' => $this->request->getPost('note'), 'created_at' => date('Y-m-d H:i:s'),
        ]);
        $account = $this->db->table('tuck_accounts')->where('id', $accountId)->get()->getRowArray();
        $this->db->table('tuck_accounts')->where('id', $accountId)->update(['balance_cents' => (int) $account['balance_cents'] + $signed]);

        return redirect()->to('/admin/boarding/tuck');
    }
}
