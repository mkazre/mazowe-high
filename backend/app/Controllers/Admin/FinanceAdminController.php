<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class FinanceAdminController extends BaseController
{
    public function feeStructures()
    {
        $rows = $this->db->table('fee_structures fs')
            ->select('fs.*, yg.name as year_group, t.name as term')
            ->join('year_groups yg', 'yg.id = fs.year_group_id', 'left')
            ->join('terms t', 't.id = fs.term_id', 'left')
            ->get()->getResultArray();

        return view('admin/finance/fee_structures', [
            'title' => 'Fee structures', 'crumb' => 'Finance', 'active' => 'finance', 'rows' => $rows,
            'yearGroups' => $this->db->table('year_groups')->get()->getResultArray(),
            'terms' => $this->db->table('terms')->get()->getResultArray(),
        ]);
    }

    public function storeFeeStructure()
    {
        $this->db->table('fee_structures')->insert([
            'year_group_id' => $this->request->getPost('year_group_id') ?: null,
            'term_id'       => $this->request->getPost('term_id') ?: null,
            'description'   => $this->request->getPost('description'),
            'amount_cents'  => (int) round(((float) $this->request->getPost('amount')) * 100),
        ]);

        return redirect()->to('/admin/finance/fee-structures');
    }

    public function deleteFeeStructure(int $id)
    {
        $this->db->table('fee_structures')->where('id', $id)->delete();

        return redirect()->to('/admin/finance/fee-structures');
    }

    public function invoices()
    {
        $rows = $this->db->table('invoices i')
            ->select('i.*, s.first_name, s.last_name')
            ->join('students s', 's.id = i.student_id')
            ->orderBy('i.created_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/finance/invoices', [
            'title' => 'Invoices & payments', 'crumb' => 'Finance', 'active' => 'finance', 'rows' => $rows,
            'students' => $this->db->table('students')->get()->getResultArray(),
        ]);
    }

    public function storeInvoice()
    {
        $studentId = (int) $this->request->getPost('student_id');
        $amount = (int) round(((float) $this->request->getPost('amount')) * 100);
        $this->db->table('invoices')->insert([
            'student_id'     => $studentId,
            'invoice_number' => 'MH-INV-' . date('y') . '-' . strtoupper(bin2hex(random_bytes(3))),
            'total_cents'    => $amount,
            'paid_cents'     => 0,
            'status'         => 'unpaid',
            'due_date'       => $this->request->getPost('due_date') ?: null,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $invoiceId = (int) $this->db->insertID();
        $this->db->table('invoice_lines')->insert(['invoice_id' => $invoiceId, 'description' => $this->request->getPost('description') ?: 'Fees', 'amount_cents' => $amount]);
        session()->setFlashdata('success', 'Invoice created.');

        return redirect()->to('/admin/finance/invoices');
    }

    public function invoice(int $id)
    {
        $invoice = $this->db->table('invoices i')->select('i.*, s.first_name, s.last_name')->join('students s', 's.id = i.student_id')->where('i.id', $id)->get()->getRowArray();
        $lines = $this->db->table('invoice_lines')->where('invoice_id', $id)->get()->getResultArray();
        $payments = $this->db->table('payments')->where('invoice_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();

        return view('admin/finance/invoice', ['title' => $invoice['invoice_number'], 'crumb' => 'Finance', 'active' => 'finance', 'invoice' => $invoice, 'lines' => $lines, 'payments' => $payments]);
    }

    public function recordCashPayment(int $id)
    {
        $invoice = $this->db->table('invoices')->where('id', $id)->get()->getRowArray();
        $amount = (int) round(((float) $this->request->getPost('amount')) * 100);

        $this->db->table('payments')->insert([
            'invoice_id' => $id, 'method' => 'cash', 'amount_cents' => $amount,
            'reference' => 'CASH-' . strtoupper(bin2hex(random_bytes(3))), 'status' => 'success', 'is_test' => 0, 'created_at' => date('Y-m-d H:i:s'),
        ]);
        $newPaid = (int) $invoice['paid_cents'] + $amount;
        $this->db->table('invoices')->where('id', $id)->update([
            'paid_cents' => $newPaid, 'status' => $newPaid >= (int) $invoice['total_cents'] ? 'paid' : 'partial',
        ]);
        session()->setFlashdata('success', 'Cash payment recorded.');

        return redirect()->to('/admin/finance/invoices/' . $id);
    }
}
