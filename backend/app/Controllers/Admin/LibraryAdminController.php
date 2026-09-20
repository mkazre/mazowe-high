<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class LibraryAdminController extends BaseController
{
    public function catalogue()
    {
        $rows = $this->db->table('catalogue_items')->get()->getResultArray();
        $loans = $this->db->table('loans l')
            ->select('l.*, c.title, s.first_name, s.last_name')
            ->join('catalogue_items c', 'c.id = l.catalogue_item_id')
            ->join('students s', 's.id = l.student_id')
            ->where('l.returned_at', null)
            ->get()->getResultArray();

        return view('admin/library/catalogue', [
            'title' => 'Catalogue & loans', 'crumb' => 'Transport & library', 'active' => 'library',
            'rows' => $rows, 'loans' => $loans, 'students' => $this->db->table('students')->get()->getResultArray(),
        ]);
    }

    public function storeItem()
    {
        $this->db->table('catalogue_items')->insert([
            'title' => $this->request->getPost('title'), 'author' => $this->request->getPost('author'),
            'isbn' => $this->request->getPost('isbn'), 'copies_total' => (int) $this->request->getPost('copies_total') ?: 1,
        ]);

        return redirect()->to('/admin/library/catalogue');
    }

    public function issueLoan()
    {
        $this->db->table('loans')->insert([
            'catalogue_item_id' => (int) $this->request->getPost('catalogue_item_id'),
            'student_id'        => (int) $this->request->getPost('student_id'),
            'borrowed_at'       => date('Y-m-d'),
            'due_at'            => date('Y-m-d', strtotime('+14 days')),
        ]);

        return redirect()->to('/admin/library/catalogue');
    }

    public function returnLoan(int $id)
    {
        $this->db->table('loans')->where('id', $id)->update(['returned_at' => date('Y-m-d')]);

        return redirect()->to('/admin/library/catalogue');
    }
}
