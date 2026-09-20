<?php

namespace App\Controllers\Api\V1;

class FinanceController extends ApiBaseController
{
    public function invoices()
    {
        $ctx = $this->context();
        if (! $ctx['student_ids']) {
            return $this->response->setJSON(['ok' => true, 'data' => []]);
        }

        $rows = $this->db->table('invoices')->whereIn('student_id', $ctx['student_ids'])->orderBy('created_at', 'DESC')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function invoice(int $id)
    {
        $ctx = $this->context();
        $invoice = $this->db->table('invoices')->where('id', $id)->get()->getRowArray();
        if (! $invoice || ! $this->canAccessStudent((int) $invoice['student_id'], $ctx)) {
            return $this->denied();
        }
        $invoice['lines'] = $this->db->table('invoice_lines')->where('invoice_id', $id)->get()->getResultArray();
        $invoice['payments'] = $this->db->table('payments')->where('invoice_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $invoice]);
    }

    /** Sandbox payment initiation — every gateway is simulated (no live merchant credentials configured).
     *  Marks the payment "pending" and a matching /verify call flips it to "success", crediting the invoice. */
    public function payInitiate()
    {
        $invoiceId = (int) ($this->request->getJsonVar('invoice_id') ?? 0);
        $method    = (string) ($this->request->getJsonVar('method') ?? '');
        $ctx       = $this->context();

        $invoice = $this->db->table('invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (! $invoice || ! $this->canAccessStudent((int) $invoice['student_id'], $ctx)) {
            return $this->denied();
        }
        if (! in_array($method, ['ecocash', 'zipit', 'card', 'innbucks', 'cash', 'paypal'], true)) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Unknown payment method'])->setStatusCode(422);
        }

        $remaining = (int) $invoice['total_cents'] - (int) $invoice['paid_cents'];
        $reference = strtoupper($method) . '-' . bin2hex(random_bytes(4));

        $this->db->table('payments')->insert([
            'invoice_id' => $invoiceId, 'method' => $method, 'amount_cents' => $remaining,
            'reference' => $reference, 'status' => 'pending', 'is_test' => 1, 'created_at' => date('Y-m-d H:i:s'),
        ]);
        $paymentId = (int) $this->db->insertID();

        return $this->response->setJSON([
            'ok' => true, 'payment_id' => $paymentId, 'reference' => $reference, 'test_mode' => true,
            'message' => 'Sandbox payment created — no real merchant account is connected yet. Call /payments/' . $paymentId . '/verify to simulate confirmation.',
        ]);
    }

    public function payVerify(int $id)
    {
        $payment = $this->db->table('payments')->where('id', $id)->get()->getRowArray();
        if (! $payment) return $this->response->setJSON(['ok' => false])->setStatusCode(404);

        $ctx = $this->context();
        $invoice = $this->db->table('invoices')->where('id', $payment['invoice_id'])->get()->getRowArray();
        if (! $invoice || ! $this->canAccessStudent((int) $invoice['student_id'], $ctx)) {
            return $this->denied();
        }

        if ($payment['status'] === 'pending') {
            $this->db->table('payments')->where('id', $id)->update(['status' => 'success']);
            $newPaid = (int) $invoice['paid_cents'] + (int) $payment['amount_cents'];
            $this->db->table('invoices')->where('id', $invoice['id'])->update([
                'paid_cents' => $newPaid,
                'status'     => $newPaid >= (int) $invoice['total_cents'] ? 'paid' : 'partial',
            ]);
        }

        return $this->response->setJSON(['ok' => true, 'status' => 'success']);
    }
}
